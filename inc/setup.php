<?php
/**
 * Optional first-run setup. Existing content and WooCommerce settings are preserved.
 */

defined( 'ABSPATH' ) || exit;

function vasella_setup_menu() {
	add_theme_page( 'Vasella', 'Vasella', 'manage_options', 'vasella-setup', 'vasella_setup_screen' );
}
add_action( 'admin_menu', 'vasella_setup_menu' );

function vasella_setup_screen() {
	$front_id = (int) get_option( 'page_on_front' );
	$result   = isset( $_GET['result'] ) ? sanitize_key( wp_unslash( $_GET['result'] ) ) : '';
	$messages = array(
		'home'     => array( 'success', 'The homepage is ready. Open it to edit the content.' ),
		'imported' => array( 'success', 'Demo products were added as drafts. Review and publish them from the product list.' ),
		'exists'   => array( 'info', 'All demo products are already in the product list.' ),
		'busy'     => array( 'warning', 'Setup is already running. Try again when it finishes.' ),
		'error'    => array( 'error', 'The action failed. Check your file upload permissions and try again.' ),
	);
	?>
	<div class="wrap">
		<h1>Vasella</h1>
		<?php if ( isset( $messages[ $result ] ) ) : ?>
			<div class="notice notice-<?php echo esc_attr( $messages[ $result ][0] ); ?>"><p><?php echo esc_html( $messages[ $result ][1] ); ?></p></div>
		<?php endif; ?>
		<h2>1. Homepage</h2>
		<p>Choose a static homepage or use the button below. Activate ACF to see the Vasella fields in the page editor.</p>
		<?php if ( $front_id && 'page' === get_option( 'show_on_front' ) && get_edit_post_link( $front_id ) ) : ?>
			<p><a class="button button-primary" href="<?php echo esc_url( get_edit_post_link( $front_id ) ); ?>">Edit homepage</a></p>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'vasella_setup' ); ?>
				<input type="hidden" name="action" value="vasella_setup">
				<input type="hidden" name="vasella_task" value="home">
				<?php submit_button( 'Prepare homepage', 'primary' ); ?>
			</form>
		<?php endif; ?>

		<h2>2. Plugins and store</h2>
		<ul>
			<li><?php echo function_exists( 'acf_add_local_field_group' ) ? 'ACF is active.' : 'Install and activate the free Advanced Custom Fields plugin.'; ?></li>
			<li><?php echo vasella_has_shop() ? 'WooCommerce is active.' : 'Install and activate WooCommerce.'; ?></li>
		</ul>
		<?php if ( vasella_has_shop() ) : ?>
			<p>Configure currency, shop pages, shipping, and payments in WooCommerce.</p>
			<?php if ( current_user_can( 'manage_woocommerce' ) ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'vasella_setup' ); ?>
					<input type="hidden" name="action" value="vasella_setup">
					<input type="hidden" name="vasella_task" value="demo">
					<?php submit_button( 'Import 6 demo products as drafts', 'secondary' ); ?>
				</form>
				<p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>">View products</a></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
}

function vasella_setup_action() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'vasella' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'vasella_setup' );

	$task   = isset( $_POST['vasella_task'] ) ? sanitize_key( wp_unslash( $_POST['vasella_task'] ) ) : '';
	$result = 'error';

	// The option key is unique, so concurrent requests cannot both run the import.
	if ( ! add_option( 'vasella_setup_running', 1, '', false ) ) {
		$result = 'busy';
	} else {
		register_shutdown_function( 'delete_option', 'vasella_setup_running' );

		try {
			if ( 'home' === $task ) {
				vasella_prepare_front_page();
				$result = 'home';
			} elseif ( 'demo' === $task && vasella_has_shop() && current_user_can( 'manage_woocommerce' ) && current_user_can( 'upload_files' ) ) {
				$result = vasella_import_demo() ? 'imported' : 'exists';
			}
		} catch ( Exception $error ) {
			$result = 'error';
		}
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'vasella-setup', 'result' => $result ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_vasella_setup', 'vasella_setup_action' );

function vasella_prepare_front_page() {
	$page_id = (int) get_option( 'page_on_front' );

	if ( ! $page_id || 'publish' !== get_post_status( $page_id ) || 'page' !== get_post_type( $page_id ) ) {
		$page_id = (int) get_option( 'vasella_created_front_page' );
		if ( ! $page_id || 'publish' !== get_post_status( $page_id ) || 'page' !== get_post_type( $page_id ) ) {
			$page_id = wp_insert_post(
				array( 'post_title' => 'Home', 'post_name' => 'vasella-home', 'post_type' => 'page', 'post_status' => 'publish' ),
				true
			);
			if ( is_wp_error( $page_id ) || ! $page_id ) {
				throw new RuntimeException( 'Could not create the front page.' );
			}
			update_option( 'vasella_created_front_page', $page_id, false );
		}
	}

	update_option( 'page_on_front', $page_id );
	update_option( 'show_on_front', 'page' );
}

function vasella_import_demo_image( $asset, $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_vasella_demo_asset',
			'meta_value'     => $asset,
		)
	);
	if ( $existing && is_file( get_attached_file( $existing[0] ) ) ) {
		return (int) $existing[0];
	}

	$temp = wp_tempnam( $asset );
	if ( ! $temp || ! copy( get_theme_file_path( 'assets/images/' . $asset ), $temp ) ) {
		if ( $temp && is_file( $temp ) ) {
			unlink( $temp );
		}
		throw new RuntimeException( 'Could not prepare a demo image.' );
	}

	$id = media_handle_sideload( array( 'name' => 'vasella-' . $asset, 'tmp_name' => $temp ), 0, $title );
	if ( is_wp_error( $id ) ) {
		if ( is_file( $temp ) ) {
			unlink( $temp );
		}
		throw new RuntimeException( $id->get_error_message() );
	}
	update_post_meta( $id, '_vasella_demo_asset', $asset );
	update_post_meta( $id, '_wp_attachment_image_alt', $title );

	return $id;
}

function vasella_import_demo() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$content = vasella_content();
	$terms   = array();
	foreach ( $content['collections'] as $category ) {
		$term = get_term_by( 'slug', $category['slug'], 'product_cat' );
		if ( $term ) {
			$terms[ $category['slug'] ] = $term->term_id;
			continue;
		}

		$id     = vasella_import_demo_image( $category['image'], $category['title'] );
		$insert = wp_insert_term( $category['title'], 'product_cat', array( 'slug' => $category['slug'] ) );
		if ( is_wp_error( $insert ) ) {
			throw new RuntimeException( $insert->get_error_message() );
		}
		$terms[ $category['slug'] ] = $insert['term_id'];
		update_term_meta( $insert['term_id'], 'thumbnail_id', $id );
	}

	$created = 0;
	foreach ( $content['products'] as $index => $item ) {
		if ( wc_get_product_id_by_sku( $item['sku'] ) ) {
			continue;
		}

		$image_id = vasella_import_demo_image( $item['image'], $item['title'] );
		$product  = new WC_Product_Simple();
		$product->set_name( $item['title'] );
		$product->set_slug( 'vasella-' . $item['id'] );
		$product->set_sku( $item['sku'] );
		$product->set_status( 'draft' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_featured( $item['featured'] );
		$product->set_menu_order( $index );
		$product->set_regular_price( (string) $item['price'] );
		$product->set_description( $item['description'] );
		$product->set_short_description( $item['material'] );
		$product->set_height( (string) wc_get_dimension( $item['height'], get_option( 'woocommerce_dimension_unit', 'cm' ), 'cm' ) );
		$product->set_image_id( $image_id );
		$product->set_category_ids( array_map( static function ( $slug ) use ( $terms ) { return $terms[ $slug ]; }, $item['categories'] ) );
		$product->update_meta_data( 'vasella_product_badge', $item['badge'] );
		$product->update_meta_data( '_vasella_product_badge', 'field_vasella_product_badge' );
		$product->update_meta_data( 'vasella_product_badge_tone', $item['tone'] );
		$product->update_meta_data( '_vasella_product_badge_tone', 'field_vasella_product_badge_tone' );

		if ( ! $product->save() ) {
			throw new RuntimeException( 'Could not save a demo product.' );
		}
		++$created;
	}
	return $created;
}
