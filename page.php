<?php
/**
 * Pages, including the WooCommerce block or shortcode cart and checkout.
 */

get_header();
?>
<main id="main-content" class="wrap content-shell" tabindex="-1">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?>>
			<h1 class="content-title"><?php the_title(); ?></h1>
			<div class="content-body">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
			<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
