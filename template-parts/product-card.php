<?php
/**
 * WooCommerce product on the front page.
 */

defined( 'ABSPATH' ) || exit;

$item  = $args['product'];
$id    = $item->get_id();
$title = $item->get_name();
$url   = $item->get_permalink();
$badge = (string) get_post_meta( $id, 'vasella_product_badge', true );
$tone  = 'clay' === get_post_meta( $id, 'vasella_product_badge_tone', true ) ? ' product-card__badge--clay' : '';
?>
<article class="product-card">
	<a class="product-card__photo" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
		<?php echo $item->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); ?>
	</a>
	<?php if ( $badge ) : ?><span class="product-card__badge<?php echo esc_attr( $tone ); ?>"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
	<?php vasella_wishlist_button( (string) $id, $title, $url, vasella_product_image_url( $item ) ); ?>
	<div class="product-card__meta">
		<div>
			<a class="product-card__title" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a>
			<span class="product-card__price"><?php echo wp_kses_post( $item->get_price_html() ); ?></span>
		</div>
		<div class="product-card__actions"><?php vasella_home_cart_button( $item ); ?></div>
	</div>
</article>
