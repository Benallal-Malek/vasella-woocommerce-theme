<?php
/**
 * Display-only sample cards until the store has published products.
 */

defined( 'ABSPATH' ) || exit;
?>
<p class="demo-note"><?php esc_html_e( 'Demo storefront', 'vasella' ); ?></p>
<div class="product-grid">
	<?php foreach ( vasella_content()['products'] as $item ) : ?>
		<?php if ( ! $item['featured'] ) { continue; } ?>
		<article class="product-card">
			<div class="product-card__photo"><?php vasella_photo( $item['image'], 0, '', false, $item['title'] ); ?></div>
			<?php if ( $item['badge'] ) : ?>
				<span class="product-card__badge<?php echo 'clay' === $item['tone'] ? ' product-card__badge--clay' : ''; ?>"><?php echo esc_html( $item['badge'] ); ?></span>
			<?php endif; ?>
			<div class="product-card__meta">
				<div>
					<span class="product-card__title"><?php echo esc_html( $item['title'] ); ?></span>
					<span class="product-card__price"><?php echo esc_html( 'RUB ' . number_format( $item['price'], 0, '.', ',' ) ); ?></span>
				</div>
			</div>
		</article>
	<?php endforeach; ?>
</div>
