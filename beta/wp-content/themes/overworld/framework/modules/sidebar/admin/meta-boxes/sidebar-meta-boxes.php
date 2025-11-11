<?php

if ( ! function_exists( 'overworld_edge_map_sidebar_meta' ) ) {
	function overworld_edge_map_sidebar_meta() {
		$edgtf_sidebar_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => apply_filters( 'overworld_edge_filter_set_scope_for_meta_boxes', array( 'page' ), 'sidebar_meta' ),
				'title' => esc_html__( 'Sidebar', 'overworld' ),
				'name'  => 'sidebar_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_sidebar_layout_meta',
				'type'        => 'select',
				'label'       => esc_html__( 'Sidebar Layout', 'overworld' ),
				'description' => esc_html__( 'Choose the sidebar layout', 'overworld' ),
				'parent'      => $edgtf_sidebar_meta_box,
                'options'       => overworld_edge_get_custom_sidebars_options( true )
			)
		);
		
		$edgtf_custom_sidebars = overworld_edge_get_custom_sidebars();
		if ( count( $edgtf_custom_sidebars ) > 0 ) {
			overworld_edge_create_meta_box_field(
				array(
					'name'        => 'edgtf_custom_sidebar_area_meta',
					'type'        => 'selectblank',
					'label'       => esc_html__( 'Choose Widget Area in Sidebar', 'overworld' ),
					'description' => esc_html__( 'Choose Custom Widget area to display in Sidebar"', 'overworld' ),
					'parent'      => $edgtf_sidebar_meta_box,
					'options'     => $edgtf_custom_sidebars,
					'args'        => array(
						'select2' => true
					)
				)
			);
		}
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_sidebar_meta', 31 );
}