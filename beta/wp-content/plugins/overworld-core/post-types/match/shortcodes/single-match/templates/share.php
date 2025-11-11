<?php if ( overworld_edge_options()->getOptionValue( 'enable_social_share' ) == 'yes' && overworld_edge_options()->getOptionValue( 'enable_social_share_on_match' ) == 'yes' ) : ?>
	<div class="edgtf-match-social-share">
		<?php
		/**
		 * Available params type, icon_type and title
		 *
		 * Return social share html
		 */
		echo overworld_edge_get_social_share_html( array( 'type'  => 'list' ) ); ?>
	</div>
<?php endif; ?>