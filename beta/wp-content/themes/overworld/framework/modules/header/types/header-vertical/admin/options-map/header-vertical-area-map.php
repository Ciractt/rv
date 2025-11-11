<?php

if ( ! function_exists( 'overworld_edge_get_hide_dep_for_header_vertical_area_options' ) ) {
	function overworld_edge_get_hide_dep_for_header_vertical_area_options() {
		$hide_dep_options = apply_filters( 'overworld_edge_filter_header_vertical_hide_global_option', $hide_dep_options = array() );
		
		return $hide_dep_options;
	}
}

if ( ! function_exists( 'overworld_edge_header_vertical_options_map' ) ) {
	function overworld_edge_header_vertical_options_map( $panel_header ) {
		$hide_dep_options = overworld_edge_get_hide_dep_for_header_vertical_area_options();
		
		$vertical_area_container = overworld_edge_add_admin_container_no_style(
			array(
				'parent'          => $panel_header,
				'name'            => 'header_vertical_area_container',
				'dependency' => array(
					'hide' => array(
						'header_options'  => $hide_dep_options
					)
				)
			)
		);
		
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $vertical_area_container,
				'name'   => 'menu_area_style',
				'title'  => esc_html__( 'Vertical Area Style', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'        => 'vertical_header_background_color',
				'type'        => 'color',
				'label'       => esc_html__( 'Background Color', 'overworld' ),
				'description' => esc_html__( 'Set background color for vertical menu', 'overworld' ),
				'parent'      => $vertical_area_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'vertical_header_background_image',
				'type'          => 'image',
				'default_value' => '',
				'label'         => esc_html__( 'Background Image', 'overworld' ),
				'description'   => esc_html__( 'Set background image for vertical menu', 'overworld' ),
				'parent'        => $vertical_area_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $vertical_area_container,
				'type'          => 'yesno',
				'name'          => 'vertical_header_shadow',
				'default_value' => 'no',
				'label'         => esc_html__( 'Shadow', 'overworld' ),
				'description'   => esc_html__( 'Set shadow on vertical header', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $vertical_area_container,
				'type'          => 'yesno',
				'name'          => 'vertical_header_border',
				'default_value' => 'no',
				'label'         => esc_html__( 'Vertical Area Border', 'overworld' ),
				'description'   => esc_html__( 'Set border on vertical area', 'overworld' )
			)
		);
		
		$vertical_header_shadow_border_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $vertical_area_container,
				'name'            => 'vertical_header_shadow_border_container',
				'dependency' => array(
					'hide' => array(
						'vertical_header_border'  => 'no'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $vertical_header_shadow_border_container,
				'type'          => 'color',
				'name'          => 'vertical_header_border_color',
				'default_value' => '',
				'label'         => esc_html__( 'Border Color', 'overworld' ),
				'description'   => esc_html__( 'Set border color for vertical area', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $vertical_area_container,
				'type'          => 'yesno',
				'name'          => 'vertical_header_bottom_widget',
				'default_value' => 'no',
				'label'         => esc_html__( 'Bottom Widget', 'overworld' ),
				'description'   => esc_html__( 'Set second widget to bottom of vertical header', 'overworld' ),
			)
		);
		
		do_action( 'overworld_edge_header_vertical_area_additional_options', $panel_header );
	}
	
	add_action( 'overworld_edge_action_additional_header_menu_area_options_map', 'overworld_edge_header_vertical_options_map' );
}