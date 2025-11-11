<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_standard_options' ) ) {
	function overworld_edge_get_hide_dep_for_header_standard_options() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_standard_hide_global_option', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_header_standard_map' ) ) {
	function overworld_edge_header_standard_map( $parent ) {
		$hide_dep_options = overworld_edge_get_hide_dep_for_header_standard_options();
		
		overworld_edge_add_admin_field(
			array(
				'parent'          => $parent,
				'type'            => 'select',
				'name'            => 'set_menu_area_position',
				'default_value'   => 'right',
				'label'           => esc_html__( 'Choose Menu Area Position', 'overworld' ),
				'description'     => esc_html__( 'Select menu area position in your header', 'overworld' ),
				'options'         => array(
					'right'  => esc_html__( 'Right', 'overworld' ),
					'left'   => esc_html__( 'Left', 'overworld' ),
					'center' => esc_html__( 'Center', 'overworld' )
				),
				'dependency' => array(
					'hide' => array(
						'header_options'  => $hide_dep_options
					)
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_header_menu_area_options_map', 'overworld_edge_header_standard_map' );
}