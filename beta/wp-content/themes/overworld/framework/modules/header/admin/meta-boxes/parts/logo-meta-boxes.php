<?php

if ( ! function_exists( 'overworld_edge_logo_meta_box_map' ) ) {
	function overworld_edge_logo_meta_box_map() {
		
		$logo_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => apply_filters( 'overworld_edge_filter_set_scope_for_meta_boxes', array( 'page', 'post' ), 'logo_meta' ),
				'title' => esc_html__( 'Logo', 'overworld' ),
				'name'  => 'logo_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_image_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Logo Image - Default', 'overworld' ),
				'description' => esc_html__( 'Choose a default logo image to display ', 'overworld' ),
				'parent'      => $logo_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_image_dark_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Logo Image - Dark', 'overworld' ),
				'description' => esc_html__( 'Choose a default logo image to display ', 'overworld' ),
				'parent'      => $logo_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_image_light_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Logo Image - Light', 'overworld' ),
				'description' => esc_html__( 'Choose a default logo image to display ', 'overworld' ),
				'parent'      => $logo_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_image_sticky_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Logo Image - Sticky', 'overworld' ),
				'description' => esc_html__( 'Choose a default logo image to display ', 'overworld' ),
				'parent'      => $logo_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_logo_image_mobile_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Logo Image - Mobile', 'overworld' ),
				'description' => esc_html__( 'Choose a default logo image to display ', 'overworld' ),
				'parent'      => $logo_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_logo_enable_box_shadow_meta',
				'type'          => 'select',
				'parent'        => $logo_meta_box,
				'label'         => esc_html__( 'Enable shadow around logo', 'overworld' ),
				'description'   => esc_html__( 'This option will enable shadow around logo', 'overworld' ),
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_logo_allow_outside_header_meta',
				'type'          => 'select',
				'parent'        => $logo_meta_box,
				'label'         => esc_html__( 'Logo outside header box', 'overworld' ),
				'description'   => esc_html__( 'This option will allow logo to be outside header', 'overworld' ),
				'default_value' => '',
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_logo_meta_box_map', 47 );
}