<?php

if ( ! function_exists( 'overworld_edge_register_button_widget' ) ) {
	/**
	 * Function that register button widget
	 */
	function overworld_edge_register_button_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassButtonWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_button_widget' );
}