<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_vertical_area_meta_boxes' ) ) {
	function overworld_edge_get_hide_dep_for_header_vertical_area_meta_boxes() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_vertical_hide_meta_boxes', $hide_dep_options = array( '' => '' ) );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_header_vertical_area_meta_options_map' ) ) {
	function overworld_edge_header_vertical_area_meta_options_map( $header_meta_box ) {
		$hide_dep_options = overworld_edge_get_hide_dep_for_header_vertical_area_meta_boxes();
		
		$header_vertical_area_meta_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $header_meta_box,
				'name'            => 'header_vertical_area_container',
				'dependency' => array(
					'hide' => array(
						'edgtf_header_type_meta' => $hide_dep_options
					)
				)
			)
		);
		
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $header_vertical_area_meta_container,
				'name'   => 'vertical_area_style',
				'title'  => esc_html__( 'Vertical Area Style', 'overworld' )
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_vertical_header_background_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Background Color', 'overworld' ),
				'description' => esc_html__( 'Set background color for vertical menu', 'overworld' ),
				'parent'      => $header_vertical_area_meta_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_vertical_header_background_image_meta',
				'type'          => 'image',
				'default_value' => '',
				'label'         => esc_html__( 'Background Image', 'overworld' ),
				'description'   => esc_html__( 'Set background image for vertical menu', 'overworld' ),
				'parent'        => $header_vertical_area_meta_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_disable_vertical_header_background_image_meta',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Disable Background Image', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will hide background image in Vertical Menu', 'overworld' ),
				'parent'        => $header_vertical_area_meta_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_vertical_header_shadow_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Shadow', 'overworld' ),
				'description'   => esc_html__( 'Set shadow on vertical menu', 'overworld' ),
				'parent'        => $header_vertical_area_meta_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_vertical_header_border_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Vertical Area Border', 'overworld' ),
				'description'   => esc_html__( 'Set border on vertical area', 'overworld' ),
				'parent'        => $header_vertical_area_meta_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		$vertical_header_border_container = overworld_edge_add_admin_container(
			array(
				'type'            => 'container',
				'name'            => 'vertical_header_border_container',
				'parent'          => $header_vertical_area_meta_container,
				'dependency' => array(
					'show' => array(
						'edgtf_vertical_header_border_meta'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_vertical_header_border_color_meta',
				'type'        => 'color',
				'label'       => esc_html__( 'Border Color', 'overworld' ),
				'description' => esc_html__( 'Choose color of border', 'overworld' ),
				'parent'      => $vertical_header_border_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_vertical_header_bottom_widget_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Bottom Widget', 'overworld' ),
				'description'   => esc_html__( 'Set second widget to bottom of vertical header', 'overworld' ),
				'parent'        => $header_vertical_area_meta_container,
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_header_area_meta_boxes_map', 'overworld_edge_header_vertical_area_meta_options_map' );
}