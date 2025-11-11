<?php

if ( ! function_exists( 'overworld_edge_sticky_header_meta_boxes_options_map' ) ) {
	function overworld_edge_sticky_header_meta_boxes_options_map( $header_meta_box ) {
		
		$sticky_amount_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $header_meta_box,
				'name'            => 'sticky_amount_container_meta_container',
				'dependency' => array(
					'hide' => array(
						'edgtf_header_behaviour_meta'  => array( '', 'no-behavior','fixed-on-scroll','sticky-header-on-scroll-up' )
					)
				)
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_sticky_header_in_grid_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Sticky Header in Grid', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will put sticky header in grid', 'overworld' ),
				'parent'        => $header_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_scroll_amount_for_sticky_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Scroll Amount for Sticky Header Appearance', 'overworld' ),
				'description' => esc_html__( 'Define scroll amount for sticky header appearance', 'overworld' ),
				'parent'      => $sticky_amount_container,
				'args'        => array(
					'col_width' => 2,
					'suffix'    => 'px'
				)
			)
		);
		
		$overworld_custom_sidebars = overworld_edge_get_custom_sidebars();
		if ( count( $overworld_custom_sidebars ) > 0 ) {
			overworld_edge_create_meta_box_field(
				array(
					'name'        => 'edgtf_custom_sticky_menu_area_sidebar_meta',
					'type'        => 'selectblank',
					'label'       => esc_html__( 'Choose Custom Widget Area In Sticky Header Menu Area', 'overworld' ),
					'description' => esc_html__( 'Choose custom widget area to display in sticky header menu area"', 'overworld' ),
					'parent'      => $header_meta_box,
					'options'     => $overworld_custom_sidebars,
					'dependency' => array(
						'show' => array(
							'edgtf_header_behaviour_meta' => array( 'sticky-header-on-scroll-up', 'sticky-header-on-scroll-down-up' )
						)
					)
				)
			);
		}
	}
	
	add_action( 'overworld_edge_action_additional_header_area_meta_boxes_map', 'overworld_edge_sticky_header_meta_boxes_options_map', 8 );
}