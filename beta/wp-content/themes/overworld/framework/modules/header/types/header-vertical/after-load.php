<?php

if ( ! function_exists( 'overworld_edge_disable_behaviors_for_header_vertical' ) ) {
	/**
	 * This function is used to disable sticky header functions that perform processing variables their used in js for this header type
	 */
	function overworld_edge_disable_behaviors_for_header_vertical( $allow_behavior ) {
		return false;
	}
	
	if ( overworld_edge_check_is_header_type_enabled( 'header-vertical', overworld_edge_get_page_id() ) ) {
		add_filter( 'overworld_edge_filter_allow_sticky_header_behavior', 'overworld_edge_disable_behaviors_for_header_vertical' );
		add_filter( 'overworld_edge_filter_allow_content_boxed_layout', 'overworld_edge_disable_behaviors_for_header_vertical' );
	}
}