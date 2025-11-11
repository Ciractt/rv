<?php

if ( ! function_exists( 'overworld_edge_register_custom_font_widget' ) ) {
	/**
	 * Function that register custom font widget
	 */
	function overworld_edge_register_custom_font_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassCustomFontWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_custom_font_widget' );
}