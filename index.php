<?php
/**
 * Posts, archives and standard WordPress search.
 */

get_header();
?>
<main id="main-content" class="wrap content-shell" tabindex="-1">
	<?php if ( is_search() ) : ?>
		<h1 class="content-title"><?php echo esc_html( sprintf( __( 'Search results for %s', 'vasella' ), get_search_query( false ) ) ); ?></h1>
	<?php elseif ( is_archive() ) : ?>
		<h1 class="content-title"><?php the_archive_title(); ?></h1>
		<?php the_archive_description(); ?>
	<?php else : ?>
		<h1 class="content-title"><?php esc_html_e( 'Posts', 'vasella' ); ?></h1>
	<?php endif; ?>
	<?php if ( have_posts() ) : ?>
		<div class="entry-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No results match your search.', 'vasella' ); ?></p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
