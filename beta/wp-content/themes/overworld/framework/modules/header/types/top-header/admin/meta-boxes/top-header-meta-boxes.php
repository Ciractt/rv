<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_top_header_area_meta_boxes' ) ) {
	function overworld_edge_get_hide_dep_for_top_header_area_meta_boxes() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_top_header_hide_meta_boxes', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_header_top_area_meta_options_map' ) ) {
	function overworld_edge_header_top_area_meta_options_map( $header_meta_box ) {
		$hide_dep_options = overworld_edge_get_hide_dep_for_top_header_area_meta_boxes();
		
		$top_header_container = overworld_edge_add_admin_container_no_style(
			array(
				'type'            => 'container',
				'name'            => 'top_header_container',
				'parent'          => $header_meta_box,
				'dependency' => array(
					'hide' => array(
						'edgtf_header_type_meta'  => $hide_dep_options
					)
				)
			)
		);
		
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $top_header_container,
				'name'   => 'top_area_style',
				'title'  => esc_html__( 'Top Area', 'overworld' )
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_top_bar_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Header Top Bar', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will show header top bar area', 'overworld' ),
				'parent'        => $top_header_container,
				'options'       => overworld_edge_get_yes_no_select_array(),
			)
		);
		
		$top_bar_container = overworld_edge_add_admin_container_no_style(
			array(
				'name'            => 'top_bar_container_no_style',
				'parent'          => $top_header_container,
				'dependency' => array(
					'show' => array(
						'edgtf_top_bar_meta' => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_top_bar_in_grid_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Top Bar In Grid', 'overworld' ),
				'description'   => esc_html__( 'Set top bar content to be in grid', 'overworld' ),
				'parent'        => $top_bar_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'   => 'edgtf_top_bar_background_color_meta',
				'type'   => 'color',
				'label'  => esc_html__( 'Top Bar Background Color', 'overworld' ),
				'parent' => $top_bar_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_top_bar_background_transparency_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Top Bar Background Color Transparency', 'overworld' ),
				'description' => esc_html__( 'Set top bar background color transparenct. Value should be between 0 and 1', 'overworld' ),
				'parent'      => $top_bar_container,
				'args'        => array(
					'col_width' => 3
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_top_bar_border_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Top Bar Border', 'overworld' ),
				'description'   => esc_html__( 'Set border on top bar', 'overworld' ),
				'parent'        => $top_bar_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		$top_bar_border_container = overworld_edge_add_admin_container(
			array(
				'type'            => 'container',
				'name'            => 'top_bar_border_container',
				'parent'          => $top_bar_container,
				'dependency' => array(
					'show' => array(
						'edgtf_top_bar_border_meta' => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_top_bar_border_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Border Color', 'overworld' ),
				'description' => esc_html__( 'Choose color for top bar border', 'overworld' ),
				'parent'      => $top_bar_border_container
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_header_area_meta_boxes_map', 'overworld_edge_header_top_area_meta_options_map' );
}