<?php
/**
 * Storefront defaults and view helpers.
 */

defined( 'ABSPATH' ) || exit;

function vasella_content() {
	static $content;
	if ( null === $content ) {
		$file    = get_theme_file_path( 'assets/data/storefront.json' );
		$content = json_decode( file_get_contents( $file ), true );
	}
	return $content;
}

function vasella_setting( $key, $default = '' ) {
	$page_id = (int) get_option( 'page_on_front' );
	$name    = 'vasella_' . $key;
	return $page_id && metadata_exists( 'post', $page_id, $name )
		? get_post_meta( $page_id, $name, true )
		: $default;
}

function vasella_copy( $key ) {
	$defaults = vasella_content()['copy'];
	return (string) vasella_setting( $key, $defaults[ $key ] ?? '' );
}

function vasella_has_shop() {
	return class_exists( 'WooCommerce' );
}

function vasella_home_url( $section = '' ) {
	return home_url( '/' ) . ( $section ? '#' . $section : '' );
}

function vasella_shop_url() {
	if ( ! vasella_has_shop() ) {
		return vasella_home_url( 'catalog' );
	}
	return wc_get_page_id( 'shop' ) > 0
		? wc_get_page_permalink( 'shop' )
		: ( get_post_type_archive_link( 'product' ) ?: vasella_home_url( 'catalog' ) );
}

function vasella_cta_url( $key, $fallback ) {
	$url = esc_url_raw( (string) vasella_setting( $key ) );
	return $url ?: $fallback;
}

function vasella_collections() {
	static $collections;
	if ( null !== $collections ) {
		return $collections;
	}

	$collections = array();
	foreach ( vasella_content()['collections'] as $index => $default ) {
		$key      = 'collection_' . ( $index + 1 );
		$category = vasella_has_shop() ? get_term_by( 'slug', $default['slug'], 'product_cat' ) : false;
		$url      = $category ? get_term_link( $category ) : vasella_shop_url();
		$thumb_id = $category ? (int) get_term_meta( $category->term_id, 'thumbnail_id', true ) : 0;

		$collections[] = array(
			'title'    => (string) vasella_setting( $key . '_title', $default['title'] ),
			'url'      => vasella_cta_url( $key . '_url', is_wp_error( $url ) ? vasella_shop_url() : $url ),
			'asset'    => $default['image'],
			'image_id' => (int) vasella_setting( $key . '_image' ) ?: $thumb_id,
		);
	}
	return $collections;
}

function vasella_image_url( $file ) {
	return get_theme_file_uri( 'assets/images/' . $file );
}

function vasella_photo( $file, $id = 0, $class = '', $priority = false, $alt = '' ) {
	$attributes = array(
		'class'   => $class,
		'alt'     => $alt,
		'loading' => $priority ? 'eager' : 'lazy',
	);
	if ( $priority ) {
		$attributes['fetchpriority'] = 'high';
	}
	if ( $id ) {
		$image = wp_get_attachment_image( $id, $priority ? 'full' : 'large', false, $attributes );
		if ( $image ) {
			echo $image; // Markup from WordPress attachment API.
			return;
		}
	}
	?>
	<img src="<?php echo esc_url( vasella_image_url( $file ) ); ?>"
		alt="<?php echo esc_attr( $alt ); ?>" class="<?php echo esc_attr( $class ); ?>"
		loading="<?php echo $priority ? 'eager' : 'lazy'; ?>" decoding="async"<?php if ( $priority ) : ?> fetchpriority="high"<?php endif; ?>>
	<?php
}

function vasella_section_photo( $key, $class = '', $priority = false ) {
	vasella_photo( vasella_content()['pictures'][ $key ], (int) vasella_setting( $key . '_image' ), $class, $priority );
}

function vasella_icon( $name ) {
	return '<svg class="icon" aria-hidden="true" focusable="false"><use href="#i-' . esc_attr( $name ) . '"></use></svg>';
}

function vasella_wordmark( $allow_custom_logo = true ) {
	if ( $allow_custom_logo && has_custom_logo() ) {
		echo str_replace( 'class="custom-logo-link"', 'class="wordmark custom-logo-link"', get_custom_logo() );
		return;
	}
	?>
	<a class="wordmark" href="<?php echo esc_url( vasella_home_url() ); ?>">
		<?php echo vasella_icon( 'vase' ); ?><span><?php echo esc_html( vasella_copy( 'brand' ) ?: get_bloginfo( 'name' ) ); ?></span>
	</a>
	<?php
}

function vasella_wishlist_button( $id, $title, $url, $image, $inline = false ) {
	?>
	<button class="wishlist-toggle<?php echo $inline ? ' wishlist-toggle--inline' : ''; ?>"
		type="button" aria-pressed="false" aria-label="<?php echo esc_attr( sprintf( __( 'Favorites — %s', 'vasella' ), $title ) ); ?>"
		data-favorite="<?php echo esc_attr( $id ); ?>" data-title="<?php echo esc_attr( $title ); ?>"
		data-url="<?php echo esc_url( $url ); ?>" data-image="<?php echo esc_url( $image ); ?>">
		<?php echo vasella_icon( 'heart' ); ?>
		<?php if ( $inline ) : ?><span><?php esc_html_e( 'Add to favorites', 'vasella' ); ?></span><?php endif; ?>
	</button>
	<?php
}
