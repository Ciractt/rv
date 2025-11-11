<?php

if ( ! function_exists( 'overworld_edge_content_bottom_options_map' ) ) {
	function overworld_edge_content_bottom_options_map() {
		
		$panel_content_bottom = overworld_edge_add_admin_panel(
			array(
				'page'  => '_page_page',
				'name'  => 'panel_content_bottom',
				'title' => esc_html__( 'Content Bottom Area Style', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'enable_content_bottom_area',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Content Bottom Area', 'overworld' ),
				'description'   => esc_html__( 'This option will enable Content Bottom area on pages', 'overworld' ),
				'parent'        => $panel_content_bottom
			)
		);
		
		$enable_content_bottom_area_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $panel_content_bottom,
				'name'            => 'enable_content_bottom_area_container',
				'dependency' => array(
					'show' => array(
						'enable_content_bottom_area'  => 'yes'
					)
				)
			)
		);
		
		$overworld_custom_sidebars = overworld_edge_get_custom_sidebars();
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'selectblank',
				'name'          => 'content_bottom_sidebar_custom_display',
				'default_value' => '',
				'label'         => esc_html__( 'Widget Area to Display', 'overworld' ),
				'description'   => esc_html__( 'Choose a Content Bottom widget area to display', 'overworld' ),
				'options'       => $overworld_custom_sidebars,
				'parent'        => $enable_content_bottom_area_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'content_bottom_in_grid',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Display in Grid', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will place Content Bottom in grid', 'overworld' ),
				'parent'        => $enable_content_bottom_area_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'        => 'color',
				'name'        => 'content_bottom_background_color',
				'label'       => esc_html__( 'Background Color', 'overworld' ),
				'description' => esc_html__( 'Choose a background color for Content Bottom area', 'overworld' ),
				'parent'      => $enable_content_bottom_area_container
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_page_options_map', 'overworld_edge_content_bottom_options_map' );
}