<?php

if ( ! function_exists( 'overworld_edge_register_sticky_sidebar_widget' ) ) {
	/**
	 * Function that register sticky sidebar widget
	 */
	function overworld_edge_register_sticky_sidebar_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassStickySidebar';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_sticky_sidebar_widget' );
}