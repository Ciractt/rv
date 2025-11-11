<form role="search" method="get" class="edgtf-searchform searchform" id="searchform-<?php echo esc_attr(rand(0, 1000)); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text"><?php esc_html_e( 'Search for:', 'overworld' ); ?></label>
	<div class="input-holder clearfix">
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search', 'overworld' ); ?>" value="" name="s" title="<?php esc_attr_e( 'Search for:', 'overworld' ); ?>"/>
		<button type="submit" class="edgtf-search-submit">
			<?php echo overworld_edge_search_svg_icon(); ?>
        </button>
	</div>
</form>