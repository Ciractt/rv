<?php

if ( ! function_exists( 'overworld_edge_register_social_icons_widget' ) ) {
	/**
	 * Function that register social icon widget
	 */
	function overworld_edge_register_social_icons_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassClassIconsGroupWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_social_icons_widget' );
}