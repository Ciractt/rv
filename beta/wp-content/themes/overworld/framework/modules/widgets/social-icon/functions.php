<?php

if ( ! function_exists( 'overworld_edge_register_social_icon_widget' ) ) {
	/**
	 * Function that register social icon widget
	 */
	function overworld_edge_register_social_icon_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassSocialIconWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_social_icon_widget' );
}