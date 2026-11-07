<?php
/**
 * WordPress comments.
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments">
	<?php if ( have_comments() ) : ?>
		<h2><?php esc_html_e( 'Comments', 'vasella' ); ?></h2>
		<ol class="comment-list"><?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true ) ); ?></ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>
