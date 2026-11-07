<?php
/**
 * WooCommerce integration. Core shop, product and checkout templates stay in WooCommerce.
 */

defined( 'ABSPATH' ) || exit;

function vasella_shop_hooks() {
	if ( ! vasella_has_shop() ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_before_main_content', 'vasella_shop_wrapper_open', 10 );
	add_action( 'woocommerce_after_main_content', 'vasella_shop_wrapper_close', 10 );
	add_action( 'woocommerce_after_shop_loop_item', 'vasella_shop_favorite', 8 );
	add_action( 'woocommerce_single_product_summary', 'vasella_single_favorite', 35 );
	add_filter( 'woocommerce_add_to_cart_fragments', 'vasella_cart_fragments' );
}
add_action( 'after_setup_theme', 'vasella_shop_hooks', 20 );

function vasella_shop_wrapper_open() {
	echo '<main id="main-content" class="wrap content-shell shop-shell" tabindex="-1">';
}

function vasella_shop_wrapper_close() {
	echo '</main>';
}

function vasella_cart_count() {
	$count = vasella_has_shop() && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	return '<span class="cart-count" data-cart-count aria-hidden="true">' . esc_html( (string) $count ) . '</span>';
}

function vasella_cart_fragments( $fragments ) {
	$fragments['span[data-cart-count]'] = vasella_cart_count();
	return $fragments;
}

function vasella_product_image_url( $item ) {
	$url = wp_get_attachment_image_url( $item->get_image_id(), 'woocommerce_thumbnail' );
	return $url ?: wc_placeholder_img_src();
}

function vasella_shop_favorite( $inline = false ) {
	global $product;

	if ( $product instanceof WC_Product ) {
		vasella_wishlist_button(
			(string) $product->get_id(),
			$product->get_name(),
			$product->get_permalink(),
			vasella_product_image_url( $product ),
			$inline
		);
	}
}

function vasella_single_favorite() {
	vasella_shop_favorite( true );
}

function vasella_home_products() {
	if ( ! vasella_has_shop() ) {
		return array();
	}

	$selected = array();
	foreach ( (array) vasella_setting( 'featured_products', array() ) as $id ) {
		$item = wc_get_product( (int) $id );
		if ( $item && 'publish' === $item->get_status() && $item->is_visible() ) {
			$selected[] = $item;
		}
	}
	if ( $selected ) {
		return array_slice( $selected, 0, 5 );
	}

	$query = array(
		'status'     => 'publish',
		'limit'      => 5,
		'visibility' => 'catalog',
	);
	if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
		$query['stock_status'] = array( 'instock', 'onbackorder' );
	}

	$featured = wc_get_products( array_merge( $query, array( 'featured' => true, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) );
	return $featured ?: wc_get_products( array_merge( $query, array( 'orderby' => 'date', 'order' => 'DESC' ) ) );
}

function vasella_home_cart_button( $item ) {
	global $product;

	$previous = $product;
	$product  = $item;
	$can_add  = $item->is_purchasable() && $item->is_in_stock();
	$classes  = array( 'button', 'product_type_' . $item->get_type(), 'vasella-loop-button' );

	if ( $can_add ) {
		$classes[] = 'add_to_cart_button';
		if ( $item->supports( 'ajax_add_to_cart' ) ) {
			$classes[] = 'ajax_add_to_cart';
		}
		if ( $item->is_type( 'simple' ) ) {
			$classes[] = 'vasella-loop-button--bag';
		}
	}

	try {
		woocommerce_template_loop_add_to_cart( array( 'class' => implode( ' ', $classes ) ) );
	} finally {
		$product = $previous;
	}
}
