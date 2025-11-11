<?php

if ( ! function_exists( 'overworld_edge_map_post_link_meta' ) ) {
	function overworld_edge_map_post_link_meta() {
		$link_post_format_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'post' ),
				'title' => esc_html__( 'Link Post Format', 'overworld' ),
				'name'  => 'post_format_link_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_link_link_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Link', 'overworld' ),
				'description' => esc_html__( 'Enter link', 'overworld' ),
				'parent'      => $link_post_format_meta_box
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_post_link_meta', 24 );
}