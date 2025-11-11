<?php

if ( ! function_exists( 'overworld_edge_mobile_menu_meta_box_map' ) ) {
	function overworld_edge_mobile_menu_meta_box_map($header_meta_box) {

		overworld_edge_add_admin_section_title(
			array(
				'parent' => $header_meta_box,
				'name'   => 'header_mobile',
				'title'  => esc_html__( 'Mobile Header in Grid', 'overworld' )
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_mobile_header_in_grid_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Mobile Header in Grid', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will put mobile header in grid', 'overworld' ),
				'parent'        => $header_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);

		$mobile_header_without_grid_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $header_meta_box,
				'name'            => 'mobile_header_without_grid_container',
				'dependency' => array(
					'show' => array(
						'edgtf_mobile_header_in_grid_meta' => 'no'
					)
				)
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_mobile_header_without_grid_padding_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Mobile Header Padding', 'overworld' ),
				'description' => esc_html__( 'Set padding for Mobile Header', 'overworld' ),
				'parent'      => $mobile_header_without_grid_container,
				'args'        => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);


	}
	
	add_action( 'overworld_edge_action_header_mobile_menu_meta_boxes_map', 'overworld_edge_mobile_menu_meta_box_map', 10 );
}