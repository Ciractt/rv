<?php

if ( ! function_exists( 'overworld_edge_register_separator_widget' ) ) {
	/**
	 * Function that register separator widget
	 */
	function overworld_edge_register_separator_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassSeparatorWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_separator_widget' );
}