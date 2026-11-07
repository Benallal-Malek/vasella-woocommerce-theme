<?php
/**
 * Site header.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php require get_theme_file_path( 'assets/icons.svg' ); ?>
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'vasella' ); ?></a>
<header class="site-header">
	<div class="wrap header-inner">
		<?php vasella_wordmark(); ?>
		<nav class="primary-nav" id="primary-navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'vasella' ); ?>">
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => false ) ); ?>
			<?php else : ?>
				<ul>
					<li>
						<details class="catalog-menu">
							<summary><?php esc_html_e( 'Catalog', 'vasella' ); ?><?php echo vasella_icon( 'chevron' ); ?></summary>
							<ul class="nav-submenu">
								<li><a href="<?php echo esc_url( vasella_shop_url() ); ?>"><?php esc_html_e( 'All vases', 'vasella' ); ?></a></li>
								<?php foreach ( vasella_collections() as $collection ) : ?>
									<li><a href="<?php echo esc_url( $collection['url'] ); ?>"><?php echo esc_html( $collection['title'] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</details>
					</li>
					<li><a href="<?php echo esc_url( vasella_home_url( 'collections' ) ); ?>"><?php echo esc_html( vasella_copy( 'collections_label' ) ); ?></a></li>
					<li><a href="<?php echo esc_url( vasella_home_url( 'new' ) ); ?>"><?php echo esc_html( vasella_copy( 'new_label' ) ); ?></a></li>
					<li><a href="<?php echo esc_url( vasella_home_url( 'about' ) ); ?>"><?php echo esc_html( vasella_copy( 'about_label' ) ); ?></a></li>
					<li><a href="<?php echo esc_url( '#contacts' ); ?>"><?php echo esc_html( vasella_copy( 'footer_contacts' ) ); ?></a></li>
				</ul>
			<?php endif; ?>
		</nav>
		<div class="header-actions">
			<button class="icon-button" type="button" data-open-dialog="site-search" aria-label="<?php esc_attr_e( 'Search', 'vasella' ); ?>" aria-haspopup="dialog"><?php echo vasella_icon( 'search' ); ?></button>
			<button class="icon-button" type="button" data-open-dialog="site-favorites" aria-label="<?php esc_attr_e( 'Favorites', 'vasella' ); ?>" aria-haspopup="dialog"><?php echo vasella_icon( 'heart' ); ?></button>
			<?php if ( vasella_has_shop() ) : ?>
				<a class="icon-button" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'vasella' ); ?>">
					<?php echo vasella_icon( 'bag' ); ?>
					<?php echo vasella_cart_count(); ?>
				</a>
			<?php endif; ?>
			<button class="icon-button menu-toggle" type="button" aria-controls="primary-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'vasella' ); ?>"><?php echo vasella_icon( 'menu' ); ?></button>
		</div>
	</div>
</header>
