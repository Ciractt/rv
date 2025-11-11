<?php

if ( ! function_exists( 'overworld_edge_set_header_vertical_type_global_option' ) ) {
	/**
	 * This function set header type value for global header option map
	 */
	function overworld_edge_set_header_vertical_type_global_option( $header_types ) {
		$header_types['header-vertical'] = array(
			'image' => OVERWORLD_EDGE_FRAMEWORK_HEADER_TYPES_ROOT . '/header-vertical/assets/img/header-vertical.png',
			'label' => esc_html__( 'Vertical', 'overworld' )
		);
		
		return $header_types;
	}
	
	add_filter( 'overworld_edge_filter_header_type_global_option', 'overworld_edge_set_header_vertical_type_global_option' );
}

if ( ! function_exists( 'overworld_edge_set_header_vertical_type_meta_boxes_option' ) ) {
	/**
	 * This function set header type value for header meta boxes map
	 */
	function overworld_edge_set_header_vertical_type_meta_boxes_option( $header_type_options ) {
		$header_type_options['header-vertical'] = esc_html__( 'Vertical', 'overworld' );
		
		return $header_type_options;
	}
	
	add_filter( 'overworld_edge_filter_header_type_meta_boxes', 'overworld_edge_set_header_vertical_type_meta_boxes_option' );
}

if ( ! function_exists( 'overworld_edge_set_hide_dep_options_header_vertical' ) ) {
	/**
	 * This function is used to hide all containers/panels for admin options when this header type is selected
	 */
	function overworld_edge_set_hide_dep_options_header_vertical( $hide_dep_options ) {
		$hide_dep_options[] = 'header-vertical';
		
		return $hide_dep_options;
	}
	
	// header global panel options
	add_filter( 'overworld_edge_filter_header_logo_area_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_menu_area_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_main_menu_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_top_header_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	
	// header global panel meta boxes
	add_filter( 'overworld_edge_filter_header_logo_area_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_menu_area_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_top_header_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );
	
	// header behavior panel options
	add_filter( 'overworld_edge_filter_header_behavior_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_fixed_header_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_sticky_header_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	
	// header behavior panel meta boxes
	add_filter( 'overworld_edge_filter_header_behavior_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );
	
	// header types panel options
	add_filter( 'overworld_edge_filter_full_screen_menu_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_centered_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_standard_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_vertical_closed_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_vertical_sliding_hide_global_option', 'overworld_edge_set_hide_dep_options_header_vertical' );
	
	// header types panel meta boxes
	add_filter( 'overworld_edge_filter_header_centered_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );
	add_filter( 'overworld_edge_filter_header_standard_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );

	// header widget area meta boxes
	//add_filter( 'overworld_edge_filter_header_widget_area_two_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );

	// header dropdown styles meta boxes
	add_filter( 'overworld_edge_filter_dropdown_hide_meta_boxes', 'overworld_edge_set_hide_dep_options_header_vertical' );
}