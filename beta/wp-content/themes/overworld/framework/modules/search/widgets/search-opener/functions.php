<?php

if ( ! function_exists( 'overworld_edge_register_search_opener_widget' ) ) {
	/**
	 * Function that register search opener widget
	 */
	function overworld_edge_register_search_opener_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassSearchOpener';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_search_opener_widget' );
}