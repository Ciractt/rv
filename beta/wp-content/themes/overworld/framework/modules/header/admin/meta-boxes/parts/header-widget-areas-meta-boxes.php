<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_widget_areas_meta_boxes' ) ) {
	function overworld_edge_get_hide_dep_for_header_widget_areas_meta_boxes() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_widget_areas_hide_meta_boxes', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_widget_area_two_meta_boxes' ) ) {
	function overworld_edge_get_hide_dep_for_header_widget_area_two_meta_boxes() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_widget_area_two_hide_meta_boxes', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_header_widget_areas_meta_options_map' ) ) {
	function overworld_edge_header_widget_areas_meta_options_map( $header_meta_box ) {
		$hide_dep_widgets 			= overworld_edge_get_hide_dep_for_header_widget_areas_meta_boxes();
		$hide_dep_widget_area_two 	= overworld_edge_get_hide_dep_for_header_widget_area_two_meta_boxes();
		
		$header_widget_areas_container = overworld_edge_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'header_widget_areas_container',
				'parent'     => $header_meta_box,
				'dependency' => array(
					'hide' => array(
						'edgtf_header_type_meta' => $hide_dep_widgets
					)
				),
				'args'       => array(
					'enable_panels_for_default_value' => true
				)
			)
		);
		
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $header_widget_areas_container,
				'name'   => 'header_widget_areas',
				'title'  => esc_html__( 'Widget Areas', 'overworld' )
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_disable_header_widget_areas_meta',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Disable Header Widget Areas', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will hide widget areas from header', 'overworld' ),
				'parent'        => $header_widget_areas_container,
			)
		);

		$header_custom_widget_areas_container = overworld_edge_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'header_custom_widget_areas_container',
				'parent'     => $header_widget_areas_container,
				'dependency' => array(
					'hide' => array(
						'edgtf_disable_header_widget_areas_meta' => 'yes'
					)
				)
			)
		);
					
		$overworld_custom_sidebars = overworld_edge_get_custom_sidebars();
		if ( count( $overworld_custom_sidebars ) > 0 ) {
			overworld_edge_create_meta_box_field(
				array(
					'name'        => 'edgtf_custom_header_widget_area_one_meta',
					'type'        => 'selectblank',
					'label'       => esc_html__( 'Choose Custom Header Widget Area One', 'overworld' ),
					'description' => esc_html__( 'Choose custom widget area to display in header widget area one', 'overworld' ),
					'parent'      => $header_custom_widget_areas_container,
					'options'     => $overworld_custom_sidebars
				)
			);
		}

		if ( count( $overworld_custom_sidebars ) > 0 ) {
			overworld_edge_create_meta_box_field(
				array(
					'name'        => 'edgtf_custom_header_widget_area_two_meta',
					'type'        => 'selectblank',
					'label'       => esc_html__( 'Choose Custom Header Widget Area Two', 'overworld' ),
					'description' => esc_html__( 'Choose custom widget area to display in header widget area two', 'overworld' ),
					'parent'      => $header_custom_widget_areas_container,
					'options'     => $overworld_custom_sidebars,
					'dependency' => array(
						'hide' => array(
							'edgtf_header_type_meta' => $hide_dep_widget_area_two
						)
					)
				)
			);
		}
		
		do_action( 'overworld_edge_header_widget_areas_additional_meta_boxes_map', $header_widget_areas_container );
	}
	
	add_action( 'overworld_edge_action_header_widget_areas_meta_boxes_map', 'overworld_edge_header_widget_areas_meta_options_map', 10, 1 );
}