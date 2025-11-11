<?php

if ( ! function_exists( 'overworld_edge_register_match_list_simple_widget' ) ) {
	/**
	 * Function that register match list simple widget
	 */
	function overworld_edge_register_match_list_simple_widget( $widgets ) {
		$widgets[] = 'OverworldCoreClassMatchListSimpleWidget';

		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_match_list_simple_widget' );
}