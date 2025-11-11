<?php

if ( ! function_exists( 'overworld_edge_register_author_info_widget' ) ) {
	/**
	 * Function that register author info widget
	 */
	function overworld_edge_register_author_info_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassAuthorInfoWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_author_info_widget' );
}