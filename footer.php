<?php
/**
 * Site footer.
 */

$phone     = vasella_copy( 'contact_phone' );
$email     = sanitize_email( vasella_copy( 'contact_email' ) );
$address   = vasella_copy( 'contact_address' );
$shortcode = trim( (string) vasella_setting( 'newsletter_shortcode' ) );
?>
<footer class="site-footer" id="contacts">
	<div class="wrap footer-inner">
		<div class="footer-brand">
			<?php vasella_wordmark( false ); ?>
			<p><?php echo esc_html( vasella_copy( 'footer_slogan' ) ); ?></p>
		</div>
		<div>
			<h2><?php echo esc_html( vasella_copy( 'footer_catalog' ) ); ?></h2>
			<?php if ( has_nav_menu( 'footer_catalog' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer_catalog', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1, 'fallback_cb' => false ) ); ?>
			<?php else : ?>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( vasella_shop_url() ); ?>"><?php esc_html_e( 'All vases', 'vasella' ); ?></a></li>
					<li><a href="<?php echo esc_url( vasella_home_url( 'collections' ) ); ?>"><?php esc_html_e( 'Shop by style', 'vasella' ); ?></a></li>
					<li><a href="<?php echo esc_url( vasella_home_url( 'new' ) ); ?>"><?php echo esc_html( vasella_copy( 'new_label' ) ); ?></a></li>
					<?php if ( vasella_has_shop() && wc_get_page_id( 'myaccount' ) > 0 ) : ?>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'My account', 'vasella' ); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div>
			<h2><?php echo esc_html( vasella_copy( 'footer_collections' ) ); ?></h2>
			<ul class="footer-links">
				<?php foreach ( vasella_collections() as $collection ) : ?>
					<li><a href="<?php echo esc_url( $collection['url'] ); ?>"><?php echo esc_html( $collection['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div>
			<h2><?php echo esc_html( vasella_copy( 'footer_about' ) ); ?></h2>
			<?php if ( has_nav_menu( 'footer_about' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer_about', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1, 'fallback_cb' => false ) ); ?>
			<?php else : ?>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( vasella_home_url( 'about' ) ); ?>"><?php esc_html_e( 'Our story', 'vasella' ); ?></a></li>
					<li><a href="<?php echo esc_url( vasella_home_url( 'craft' ) ); ?>"><?php esc_html_e( 'Craft and materials', 'vasella' ); ?></a></li>
					<?php if ( get_privacy_policy_url() ) : ?>
						<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy policy', 'vasella' ); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div>
			<h2><?php echo esc_html( vasella_copy( 'footer_contacts' ) ); ?></h2>
			<ul class="contact-list">
				<?php if ( $phone ) : ?>
					<li><?php echo vasella_icon( 'phone' ); ?><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<li>
						<?php echo vasella_icon( 'mail' ); ?>
						<?php if ( 'hello@vasella.example' === $email ) : ?>
							<span><?php echo esc_html( $email ); ?></span>
						<?php else : ?>
							<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
						<?php endif; ?>
					</li>
				<?php endif; ?>
				<?php if ( $address ) : ?><li><?php echo vasella_icon( 'pin' ); ?><span><?php echo esc_html( $address ); ?></span></li><?php endif; ?>
			</ul>
		</div>
		<div class="footer-newsletter">
			<h2><?php echo esc_html( vasella_copy( 'newsletter_title' ) ); ?></h2>
			<p><?php echo esc_html( vasella_copy( 'newsletter_text' ) ); ?></p>
			<?php if ( $shortcode && preg_match( '/^\[([\w-]+)/', $shortcode, $matches ) && shortcode_exists( $matches[1] ) ) : ?>
				<?php echo do_shortcode( $shortcode ); // Site editor supplies the newsletter plugin shortcode. ?>
			<?php else : ?>
				<div class="newsletter-form" aria-hidden="true">
					<input type="email" disabled placeholder="<?php esc_attr_e( 'Your email address', 'vasella' ); ?>">
					<button class="icon-button" type="button" disabled><?php echo vasella_icon( 'arrow' ); ?></button>
				</div>
				<p class="newsletter-note"><?php esc_html_e( 'The newsletter is coming soon.', 'vasella' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php get_template_part( 'template-parts/dialogs' ); ?>
<?php wp_footer(); ?>
</body>
</html>
