<?php $share_type = isset( $share_type ) ? $share_type : 'list';

if ( overworld_edge_is_plugin_installed( 'core' ) && overworld_edge_options()->getOptionValue( 'enable_social_share' ) === 'yes' && overworld_edge_options()->getOptionValue( 'enable_social_share_on_post' ) === 'yes' ) { ?>
	<div class="edgtf-blog-share">
		<?php echo overworld_edge_get_social_share_html( array( 'type' => $share_type, 'title' => esc_html__( 'Share:', 'overworld' ) ) ); ?>
	</div>
<?php } ?>