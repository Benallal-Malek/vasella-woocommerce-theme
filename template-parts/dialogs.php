<?php
/**
 * Shared storefront dialogs.
 */

defined( 'ABSPATH' ) || exit;
?>
<dialog class="site-dialog" id="site-search" aria-labelledby="site-search-title">
	<div class="dialog-heading">
		<h2 class="dialog-title" id="site-search-title"><?php esc_html_e( 'Search', 'vasella' ); ?></h2>
		<button class="icon-button" type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close search', 'vasella' ); ?>"><?php echo vasella_icon( 'close' ); ?></button>
	</div>
	<form class="search-form" role="search" method="get" action="<?php echo esc_url( vasella_home_url() ); ?>">
		<label class="screen-reader-text" for="vasella-search"><?php esc_html_e( 'Search query', 'vasella' ); ?></label>
		<input type="search" id="vasella-search" name="s" placeholder="<?php esc_attr_e( 'Search by name', 'vasella' ); ?>" value="<?php echo esc_attr( get_search_query( false ) ); ?>" required autofocus>
		<?php if ( vasella_has_shop() ) : ?><input type="hidden" name="post_type" value="product"><?php endif; ?>
		<button type="submit" class="button-link"><?php esc_html_e( 'Search', 'vasella' ); ?></button>
	</form>
</dialog>
<dialog class="site-dialog site-dialog--drawer" id="site-favorites" aria-labelledby="site-favorites-title">
	<div class="dialog-heading">
		<h2 class="dialog-title" id="site-favorites-title"><?php esc_html_e( 'Favorites', 'vasella' ); ?></h2>
		<button class="icon-button" type="button" data-close-dialog aria-label="<?php esc_attr_e( 'Close favorites', 'vasella' ); ?>"><?php echo vasella_icon( 'close' ); ?></button>
	</div>
	<p data-favorites-empty><?php esc_html_e( 'Save the vases you like to see them here.', 'vasella' ); ?></p>
	<ul class="saved-list" data-favorites-list></ul>
</dialog>
<div class="status-toast" role="status" data-store-status hidden></div>
