<?php
/**
 * Field groups registered with the free version of Advanced Custom Fields.
 */

defined( 'ABSPATH' ) || exit;

function vasella_register_fields() {
	$defaults = vasella_content()['copy'];
	$tabs     = array(
		'hero' => array( 'Hero', array(
			'brand'            => 'Brand name',
			'hero_title'       => array( 'Heading', 'textarea' ),
			'hero_text'        => array( 'Subheading', 'textarea' ),
			'hero_image'       => array( 'Hero image', 'image' ),
			'hero_catalog'     => 'Catalog button',
			'hero_catalog_url' => array( 'Catalog button link', 'url' ),
			'hero_about'       => 'About button',
			'hero_about_url'   => array( 'About button link', 'url' ),
		) ),
		'collections' => array( 'Collections', array(
			'collections_label' => 'Section label',
			'collections_title' => array( 'Heading', 'textarea' ),
			'collections_text'  => array( 'Description', 'textarea' ),
		) ),
		'featured' => array( 'Bestsellers', array(
			'featured_title'    => 'Heading',
			'featured_button'   => 'Button label',
			'featured_url'      => array( 'Button link', 'url' ),
		) ),
		'craft' => array( 'Craft', array(
			'craft_label'       => 'Section label',
			'craft_title'       => array( 'Heading', 'textarea' ),
			'craft_text'        => array( 'Description', 'textarea' ),
			'craft_left_image'  => array( 'Left image', 'image' ),
			'craft_right_image' => array( 'Right image', 'image' ),
			'craft_button'      => 'Button label',
			'craft_url'         => array( 'Button link', 'url' ),
		) ),
		'new' => array( 'New arrivals', array(
			'new_label'        => 'Section label',
			'new_title'        => array( 'Heading', 'textarea' ),
			'new_text'         => array( 'Description', 'textarea' ),
			'new_main_image'   => array( 'Main image', 'image' ),
			'new_top_image'    => array( 'Top image', 'image' ),
			'new_bottom_image' => array( 'Bottom image', 'image' ),
			'new_tall_image'   => array( 'Tall image on the right', 'image' ),
			'new_button'       => 'Button label',
			'new_url'          => array( 'Button link', 'url' ),
		) ),
		'about' => array( 'About', array(
			'about_label'   => 'Section label',
			'about_title'   => 'Heading',
			'about_text'    => array( 'Description', 'textarea' ),
			'value_1_title' => 'Feature 1 · heading',
			'value_1_text'  => 'Feature 1 · description',
			'value_2_title' => 'Feature 2 · heading',
			'value_2_text'  => 'Feature 2 · description',
			'value_3_title' => 'Feature 3 · heading',
			'value_3_text'  => 'Feature 3 · description',
		) ),
		'footer' => array( 'Footer', array(
			'footer_slogan'        => array( 'Tagline below the logo', 'textarea' ),
			'footer_catalog'       => 'Catalog heading',
			'footer_collections'   => 'Collections heading',
			'footer_about'         => 'About heading',
			'footer_contacts'      => 'Contact heading',
			'contact_phone'        => 'Phone',
			'contact_email'        => array( 'Email', 'email' ),
			'contact_address'      => 'Address',
			'newsletter_title'     => 'Newsletter heading',
			'newsletter_text'      => array( 'Newsletter description', 'textarea' ),
			'newsletter_shortcode' => 'Newsletter form shortcode',
		) ),
	);

	foreach ( vasella_content()['collections'] as $index => $collection ) {
		$number                                  = $index + 1;
		$key                                     = 'collection_' . $number;
		$tabs['collections'][1][ $key . '_title' ] = 'Collection ' . $number . ' · name';
		$tabs['collections'][1][ $key . '_image' ] = array( 'Collection ' . $number . ' · image', 'image' );
		$tabs['collections'][1][ $key . '_url' ]   = array( 'Collection ' . $number . ' · link', 'url' );
		$defaults[ $key . '_title' ]              = $collection['title'];
	}

	$fields = array();
	foreach ( $tabs as $tab => list( $label, $definitions ) ) {
		$fields[] = array( 'key' => 'field_vasella_tab_' . $tab, 'label' => $label, 'type' => 'tab' );

		foreach ( $definitions as $key => $definition ) {
			list( $field_label, $type ) = is_array( $definition ) ? $definition : array( $definition, 'text' );
			$field = array(
				'key'   => 'field_vasella_' . $key,
				'name'  => 'vasella_' . $key,
				'label' => $field_label,
				'type'  => $type,
			);
			if ( 'image' === $type ) {
				$field['return_format'] = 'id';
				$field['preview_size']  = 'medium';
				if ( 'hero_image' === $key ) {
					$field['instructions'] = 'For a wide banner, leave a light area on the left for the heading.';
				}
			} else {
				$field['default_value'] = $defaults[ $key ] ?? '';
			}
			if ( 'textarea' === $type ) {
				$field['rows'] = 3;
			}
			if ( 'url' === $type ) {
				$field['instructions'] = 'Leave blank to use the theme link.';
			}
			$fields[] = $field;
		}

		if ( 'featured' === $tab && vasella_has_shop() ) {
			$fields[] = array(
				'key'           => 'field_vasella_featured_products',
				'name'          => 'vasella_featured_products',
				'label'         => 'Homepage products',
				'type'          => 'relationship',
				'post_type'     => array( 'product' ),
				'post_status'   => array( 'publish' ),
				'filters'       => array( 'search' ),
				'max'           => 5,
				'return_format' => 'id',
				'instructions'  => 'Select up to five products in display order. Leave blank to show WooCommerce featured products.',
			);
		}
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_vasella_home',
			'title'    => 'Vasella · Homepage and footer',
			'fields'   => $fields,
			'location' => array( array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ) ),
		)
	);

	if ( vasella_has_shop() ) {
		acf_add_local_field_group(
			array(
				'key'      => 'group_vasella_product',
				'title'    => 'Vasella · Product display',
				'fields'   => array(
					array(
						'key'          => 'field_vasella_product_badge',
						'name'         => 'vasella_product_badge',
						'label'        => 'Product badge',
						'type'         => 'text',
						'maxlength'    => 24,
						'instructions' => 'For example, Bestseller or New.',
					),
					array(
						'key'           => 'field_vasella_product_badge_tone',
						'name'          => 'vasella_product_badge_tone',
						'label'         => 'Badge color',
						'type'          => 'select',
						'choices'       => array( 'olive' => 'Olive', 'clay' => 'Terracotta' ),
						'default_value' => 'olive',
					),
				),
				'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ) ) ),
			)
		);
	}
}
add_action( 'acf/init', 'vasella_register_fields' );
