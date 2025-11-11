<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_logo_area_meta_boxes' ) ) {
	function overworld_edge_get_hide_dep_for_header_logo_area_meta_boxes() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_logo_area_hide_meta_boxes', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}


if ( ! function_exists( 'overworld_edge_header_logo_area_meta_options_map' ) ) {
	function overworld_edge_header_logo_area_meta_options_map( $header_meta_box ) {
		$hide_dep_options = overworld_edge_get_hide_dep_for_header_logo_area_meta_boxes();
		
		$logo_area_container = overworld_edge_add_admin_container_no_style(
			array(
				'type'            => 'container',
				'name'            => 'logo_area_container',
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
				'parent' => $logo_area_container,
				'name'   => 'logo_area_style',
				'title'  => esc_html__( 'Logo Area Style', 'overworld' )
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_logo_area_in_grid_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Logo Area In Grid', 'overworld' ),
				'description'   => esc_html__( 'Set menu area content to be in grid', 'overworld' ),
				'parent'        => $logo_area_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		$logo_area_in_grid_container = overworld_edge_add_admin_container(
			array(
				'type'            => 'container',
				'name'            => 'logo_area_in_grid_container',
				'parent'          => $logo_area_container,
				'dependency' => array(
					'show' => array(
						'edgtf_logo_area_in_grid_meta'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_grid_background_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Grid Background Color', 'overworld' ),
				'description' => esc_html__( 'Set grid background color for logo area', 'overworld' ),
				'parent'      => $logo_area_in_grid_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_grid_background_transparency_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Grid Background Transparency', 'overworld' ),
				'description' => esc_html__( 'Set grid background transparency for logo area (0 = fully transparent, 1 = opaque)', 'overworld' ),
				'parent'      => $logo_area_in_grid_container,
				'args'        => array(
					'col_width' => 2
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_logo_area_in_grid_border_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Grid Area Border', 'overworld' ),
				'description'   => esc_html__( 'Set border on grid logo area', 'overworld' ),
				'parent'        => $logo_area_in_grid_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		$logo_area_in_grid_border_container = overworld_edge_add_admin_container(
			array(
				'type'            => 'container',
				'name'            => 'logo_area_in_grid_border_container',
				'parent'          => $logo_area_in_grid_container,
				'dependency' => array(
					'show' => array(
						'edgtf_logo_area_in_grid_border_meta'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_in_grid_border_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Border Color', 'overworld' ),
				'description' => esc_html__( 'Set border color for grid area', 'overworld' ),
				'parent'      => $logo_area_in_grid_border_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_background_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Background Color', 'overworld' ),
				'description' => esc_html__( 'Choose a background color for logo area', 'overworld' ),
				'parent'      => $logo_area_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_background_transparency_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Transparency', 'overworld' ),
				'description' => esc_html__( 'Choose a transparency for the logo area background color (0 = fully transparent, 1 = opaque)', 'overworld' ),
				'parent'      => $logo_area_container,
				'args'        => array(
					'col_width' => 2
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_logo_area_border_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Logo Area Border', 'overworld' ),
				'description'   => esc_html__( 'Set border on logo area', 'overworld' ),
				'parent'        => $logo_area_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		$logo_area_border_bottom_color_container = overworld_edge_add_admin_container(
			array(
				'type'            => 'container',
				'name'            => 'logo_area_border_bottom_color_container',
				'parent'          => $logo_area_container,
				'dependency' => array(
					'show' => array(
						'edgtf_logo_area_border_meta'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_border_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Border Color', 'overworld' ),
				'description' => esc_html__( 'Choose color of header bottom border', 'overworld' ),
				'parent'      => $logo_area_border_bottom_color_container
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_area_height_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Height', 'overworld' ),
				'description' => esc_html__( 'Enter logo area height (default is 90px)', 'overworld' ),
				'parent'      => $logo_area_container,
				'args'        => array(
					'col_width' => 3,
					'suffix'    => esc_html__( 'px', 'overworld' )
				)
			)
		);
		
		do_action( 'overworld_edge_action_header_logo_area_additional_meta_boxes_map', $logo_area_container );
	}
	
	add_action( 'overworld_edge_action_header_logo_area_meta_boxes_map', 'overworld_edge_header_logo_area_meta_options_map', 10, 1 );
}