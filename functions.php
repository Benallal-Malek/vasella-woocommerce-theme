<?php
/**
 * Vasella theme setup.
 */

defined( 'ABSPATH' ) || exit;

require_once get_theme_file_path( 'inc/content.php' );
require_once get_theme_file_path( 'inc/acf.php' );
require_once get_theme_file_path( 'inc/woocommerce.php' );

if ( is_admin() ) {
	require_once get_theme_file_path( 'inc/setup.php' );
}

function vasella_theme_setup() {
	load_theme_textdomain( 'vasella', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 460,
			'single_image_width'    => 900,
			'product_grid'          => array( 'default_columns' => 4, 'min_columns' => 2, 'max_columns' => 5 ),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary'        => __( 'Main navigation', 'vasella' ),
			'footer_catalog' => __( 'Footer · Catalog', 'vasella' ),
			'footer_about'   => __( 'Footer · About', 'vasella' ),
		)
	);
}
add_action( 'after_setup_theme', 'vasella_theme_setup' );

function vasella_enqueue_assets() {
	$version = wp_get_theme( get_template() )->get( 'Version' );
	wp_enqueue_style( 'vasella-storefront', get_theme_file_uri( 'assets/css/storefront.css' ), array(), $version );
	wp_enqueue_style( 'vasella-theme', get_stylesheet_uri(), array( 'vasella-storefront' ), $version );
	wp_enqueue_script( 'vasella-storefront', get_theme_file_uri( 'assets/js/storefront.js' ), array(), $version, true );

	if ( vasella_has_shop() ) {
		wp_enqueue_style( 'vasella-woocommerce', get_theme_file_uri( 'assets/css/woocommerce.css' ), array( 'vasella-theme' ), $version );
		wp_enqueue_script( 'wc-cart-fragments' );

		if ( is_front_page() && 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
			wp_enqueue_script( 'wc-add-to-cart' );
		}
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vasella_enqueue_assets', 20 );
