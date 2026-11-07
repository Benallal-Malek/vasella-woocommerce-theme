<?php
/**
 * Vasella storefront.
 */

get_header();

$products = vasella_home_products();
$shop_url = vasella_shop_url();
$new_url  = vasella_has_shop() ? add_query_arg( 'orderby', 'date', $shop_url ) : vasella_home_url( 'catalog' );
?>

<main id="main-content" tabindex="-1">
	<section class="hero hero--wide" aria-labelledby="hero-title">
		<div class="wrap hero-inner">
			<div class="hero-copy">
				<h1 id="hero-title" class="display-title"><?php echo esc_html( vasella_copy( 'hero_title' ) ); ?></h1>
				<p><?php echo esc_html( vasella_copy( 'hero_text' ) ); ?></p>
				<div class="hero-actions">
					<a class="button-link" href="<?php echo esc_url( vasella_cta_url( 'hero_catalog_url', $shop_url ) ); ?>"><?php echo esc_html( vasella_copy( 'hero_catalog' ) ); ?><?php echo vasella_icon( 'arrow' ); ?></a>
					<a class="button-link button-link--outline" href="<?php echo esc_url( vasella_cta_url( 'hero_about_url', '#about' ) ); ?>"><?php echo esc_html( vasella_copy( 'hero_about' ) ); ?></a>
				</div>
			</div>
		</div>
		<picture>
			<?php if ( ! vasella_setting( 'hero_image' ) ) : ?>
				<source media="(max-width: 570px)" srcset="<?php echo esc_url( vasella_image_url( 'hero-mobile.webp' ) ); ?>">
			<?php endif; ?>
			<?php vasella_section_photo( 'hero', 'hero-media', true ); ?>
		</picture>
	</section>

	<section class="wrap collection-grid" id="collections" aria-labelledby="collections-title">
		<div class="section-copy">
			<p class="eyebrow"><?php echo esc_html( vasella_copy( 'collections_label' ) ); ?></p>
			<h2 class="section-title" id="collections-title"><?php echo esc_html( vasella_copy( 'collections_title' ) ); ?></h2>
			<p><?php echo esc_html( vasella_copy( 'collections_text' ) ); ?></p>
		</div>
		<?php foreach ( vasella_collections() as $collection ) : ?>
			<a class="collection-card" href="<?php echo esc_url( $collection['url'] ); ?>">
				<?php vasella_photo( $collection['asset'], $collection['image_id'] ); ?>
				<span class="collection-card__label"><?php echo esc_html( $collection['title'] ); ?><?php echo vasella_icon( 'arrow' ); ?></span>
			</a>
		<?php endforeach; ?>
	</section>

	<section class="wrap featured" id="catalog" aria-labelledby="featured-title">
		<div class="section-head">
			<h2 class="section-title" id="featured-title"><?php echo esc_html( vasella_copy( 'featured_title' ) ); ?></h2>
			<?php if ( vasella_has_shop() || vasella_setting( 'featured_url' ) ) : ?>
				<a class="text-link" href="<?php echo esc_url( vasella_cta_url( 'featured_url', $shop_url ) ); ?>"><?php echo esc_html( vasella_copy( 'featured_button' ) ); ?><?php echo vasella_icon( 'arrow' ); ?></a>
			<?php endif; ?>
		</div>

		<?php if ( $products ) : ?>
			<?php if ( function_exists( 'woocommerce_output_all_notices' ) ) { woocommerce_output_all_notices(); } ?>
			<div class="product-grid">
				<?php foreach ( $products as $item ) : ?>
					<?php get_template_part( 'template-parts/product-card', null, array( 'product' => $item ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php elseif ( ! vasella_has_shop() || empty( wp_count_posts( 'product' )->publish ) ) : ?>
			<?php get_template_part( 'template-parts/demo-products' ); ?>
		<?php else : ?>
			<p class="demo-note"><?php esc_html_e( 'The featured selection is coming soon.', 'vasella' ); ?></p>
		<?php endif; ?>
	</section>

	<section class="craft" id="craft" aria-labelledby="craft-title">
		<div class="craft__left"><?php vasella_section_photo( 'craft_left' ); ?></div>
		<div class="section-copy craft__copy">
			<p class="eyebrow"><?php echo esc_html( vasella_copy( 'craft_label' ) ); ?></p>
			<h2 class="section-title" id="craft-title"><?php echo esc_html( vasella_copy( 'craft_title' ) ); ?></h2>
			<p><?php echo esc_html( vasella_copy( 'craft_text' ) ); ?></p>
			<a class="button-link button-link--outline" href="<?php echo esc_url( vasella_cta_url( 'craft_url', '#about' ) ); ?>"><?php echo esc_html( vasella_copy( 'craft_button' ) ); ?><?php echo vasella_icon( 'arrow' ); ?></a>
		</div>
		<div class="craft__right"><?php vasella_section_photo( 'craft_right' ); ?></div>
	</section>

	<section class="wrap new-section" id="new" aria-labelledby="new-title">
		<div class="section-copy new-section__copy">
			<p class="eyebrow"><?php echo esc_html( vasella_copy( 'new_label' ) ); ?></p>
			<h2 class="section-title" id="new-title"><?php echo esc_html( vasella_copy( 'new_title' ) ); ?></h2>
			<p><?php echo esc_html( vasella_copy( 'new_text' ) ); ?></p>
			<a class="button-link button-link--outline" href="<?php echo esc_url( vasella_cta_url( 'new_url', $new_url ) ); ?>"><?php echo esc_html( vasella_copy( 'new_button' ) ); ?><?php echo vasella_icon( 'arrow' ); ?></a>
		</div>
		<?php foreach ( array( 'main', 'top', 'bottom', 'tall' ) as $photo ) : ?>
			<div class="new-photo new-photo--<?php echo esc_attr( $photo ); ?>"><?php vasella_section_photo( 'new_' . $photo ); ?></div>
		<?php endforeach; ?>
	</section>

	<section class="wrap about" id="about" aria-labelledby="about-title">
		<div class="section-copy about__copy">
			<p class="eyebrow"><?php echo esc_html( vasella_copy( 'about_label' ) ); ?></p>
			<h2 class="section-title" id="about-title"><?php echo esc_html( vasella_copy( 'about_title' ) ); ?></h2>
			<p><?php echo esc_html( vasella_copy( 'about_text' ) ); ?></p>
		</div>
		<?php foreach ( array( 1 => 'leaf', 2 => 'hand', 3 => 'infinity' ) as $number => $icon ) : ?>
			<div class="about__value">
				<?php echo vasella_icon( $icon ); ?>
				<h3><?php echo esc_html( vasella_copy( 'value_' . $number . '_title' ) ); ?></h3>
				<p><?php echo esc_html( vasella_copy( 'value_' . $number . '_text' ) ); ?></p>
			</div>
		<?php endforeach; ?>
	</section>
</main>

<?php get_footer(); ?>
