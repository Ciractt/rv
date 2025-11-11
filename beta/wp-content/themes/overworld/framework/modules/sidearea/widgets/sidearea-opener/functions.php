<?php

if ( ! function_exists( 'overworld_edge_register_sidearea_opener_widget' ) ) {
	/**
	 * Function that register sidearea opener widget
	 */
	function overworld_edge_register_sidearea_opener_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassSideAreaOpener';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_sidearea_opener_widget' );
}