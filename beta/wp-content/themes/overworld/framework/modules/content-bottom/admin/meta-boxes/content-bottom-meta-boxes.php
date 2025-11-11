<?php

if ( ! function_exists( 'overworld_edge_map_content_bottom_meta' ) ) {
	function overworld_edge_map_content_bottom_meta() {
		
		$content_bottom_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => apply_filters( 'overworld_edge_filter_set_scope_for_meta_boxes', array( 'page', 'post' ), 'content_bottom_meta' ),
				'title' => esc_html__( 'Content Bottom', 'overworld' ),
				'name'  => 'content_bottom_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_enable_content_bottom_area_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Enable Content Bottom Area', 'overworld' ),
				'description'   => esc_html__( 'This option will enable Content Bottom area on pages', 'overworld' ),
				'parent'        => $content_bottom_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
		$show_content_bottom_meta_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $content_bottom_meta_box,
				'name'            => 'edgtf_show_content_bottom_meta_container',
				'dependency' => array(
					'show' => array(
						'edgtf_enable_content_bottom_area_meta' => 'yes'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_content_bottom_sidebar_custom_display_meta',
				'type'          => 'selectblank',
				'default_value' => '',
				'label'         => esc_html__( 'Sidebar to Display', 'overworld' ),
				'description'   => esc_html__( 'Choose a content bottom sidebar to display', 'overworld' ),
				'options'       => overworld_edge_get_custom_sidebars(),
				'parent'        => $show_content_bottom_meta_container,
				'args'          => array(
					'select2' => true
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'type'          => 'select',
				'name'          => 'edgtf_content_bottom_in_grid_meta',
				'default_value' => '',
				'label'         => esc_html__( 'Display in Grid', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will place content bottom in grid', 'overworld' ),
				'options'       => overworld_edge_get_yes_no_select_array(),
				'parent'        => $show_content_bottom_meta_container
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'type'        => 'color',
				'name'        => 'edgtf_content_bottom_background_color_meta',
				'label'       => esc_html__( 'Background Color', 'overworld' ),
				'description' => esc_html__( 'Choose a background color for content bottom area', 'overworld' ),
				'parent'      => $show_content_bottom_meta_container
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_content_bottom_meta', 71 );
}