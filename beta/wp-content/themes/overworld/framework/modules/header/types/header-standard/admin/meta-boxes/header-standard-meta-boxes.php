<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_standard_meta_boxes' ) ) {
	function overworld_edge_get_hide_dep_for_header_standard_meta_boxes() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_standard_hide_meta_boxes', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_header_standard_meta_map' ) ) {
	function overworld_edge_header_standard_meta_map( $parent ) {
		$hide_dep_options = overworld_edge_get_hide_dep_for_header_standard_meta_boxes();
		
		overworld_edge_create_meta_box_field(
			array(
				'parent'          => $parent,
				'type'            => 'select',
				'name'            => 'edgtf_set_menu_area_position_meta',
				'default_value'   => '',
				'label'           => esc_html__( 'Choose Menu Area Position', 'overworld' ),
				'description'     => esc_html__( 'Select menu area position in your header', 'overworld' ),
				'options'         => array(
					''       => esc_html__( 'Default', 'overworld' ),
					'left'   => esc_html__( 'Left', 'overworld' ),
					'right'  => esc_html__( 'Right', 'overworld' ),
					'center' => esc_html__( 'Center', 'overworld' )
				),
				'dependency' => array(
					'hide' => array(
						'edgtf_header_type_meta'  => $hide_dep_options
					)
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_header_area_meta_boxes_map', 'overworld_edge_header_standard_meta_map' );
}