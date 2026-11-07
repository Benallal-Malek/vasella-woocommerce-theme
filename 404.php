<?php
get_header();
?>
<main id="main-content" class="wrap content-shell" tabindex="-1">
	<h1 class="content-title"><?php esc_html_e( 'Page not found', 'vasella' ); ?></h1>
	<a class="button-link" href="<?php echo esc_url( vasella_home_url() ); ?>"><?php esc_html_e( 'Back to home', 'vasella' ); ?><?php echo vasella_icon( 'arrow' ); ?></a>
</main>
<?php get_footer(); ?>
