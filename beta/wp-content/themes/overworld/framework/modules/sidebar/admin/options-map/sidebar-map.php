<?php

if ( ! function_exists( 'overworld_edge_sidebar_options_map' ) ) {
	function overworld_edge_sidebar_options_map() {
		
		overworld_edge_add_admin_page(
			array(
				'slug'  => '_sidebar_page',
				'title' => esc_html__( 'Sidebar Area', 'overworld' ),
				'icon'  => 'fa fa-indent'
			)
		);
		
		$sidebar_panel = overworld_edge_add_admin_panel(
			array(
				'title' => esc_html__( 'Sidebar Area', 'overworld' ),
				'name'  => 'sidebar',
				'page'  => '_sidebar_page'
			)
		);
		
		overworld_edge_add_admin_field( array(
			'name'          => 'sidebar_layout',
			'type'          => 'select',
			'label'         => esc_html__( 'Sidebar Layout', 'overworld' ),
			'description'   => esc_html__( 'Choose a sidebar layout for pages', 'overworld' ),
			'parent'        => $sidebar_panel,
			'default_value' => 'no-sidebar',
            'options'       => overworld_edge_get_custom_sidebars_options()
		) );
		
		$overworld_custom_sidebars = overworld_edge_get_custom_sidebars();
		if ( count( $overworld_custom_sidebars ) > 0 ) {
			overworld_edge_add_admin_field( array(
				'name'        => 'custom_sidebar_area',
				'type'        => 'selectblank',
				'label'       => esc_html__( 'Sidebar to Display', 'overworld' ),
				'description' => esc_html__( 'Choose a sidebar to display on pages. Default sidebar is "Sidebar"', 'overworld' ),
				'parent'      => $sidebar_panel,
				'options'     => $overworld_custom_sidebars,
				'args'        => array(
					'select2' => true
				)
			) );
		}
	}
	
	add_action( 'overworld_edge_action_options_map', 'overworld_edge_sidebar_options_map', overworld_edge_set_options_map_position( 'sidebar' ) );
}