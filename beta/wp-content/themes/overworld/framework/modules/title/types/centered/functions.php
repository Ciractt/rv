<?php

if ( ! function_exists( 'overworld_edge_set_title_centered_type_for_options' ) ) {
	/**
	 * This function set centered title type value for title options map and meta boxes
	 */
	function overworld_edge_set_title_centered_type_for_options( $type ) {
		$type['centered'] = esc_html__( 'Centered', 'overworld' );
		
		return $type;
	}
	
	add_filter( 'overworld_edge_filter_title_type_global_option', 'overworld_edge_set_title_centered_type_for_options' );
	add_filter( 'overworld_edge_filter_title_type_meta_boxes', 'overworld_edge_set_title_centered_type_for_options' );
}