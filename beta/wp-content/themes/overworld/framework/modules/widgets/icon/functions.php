<?php

if ( ! function_exists( 'overworld_edge_register_icon_widget' ) ) {
	/**
	 * Function that register icon widget
	 */
	function overworld_edge_register_icon_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassIconWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_icon_widget' );
}