<?php

if ( ! function_exists( 'overworld_edge_map_post_quote_meta' ) ) {
	function overworld_edge_map_post_quote_meta() {
		$quote_post_format_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'post' ),
				'title' => esc_html__( 'Quote Post Format', 'overworld' ),
				'name'  => 'post_format_quote_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_quote_text_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Quote Text', 'overworld' ),
				'description' => esc_html__( 'Enter Quote text', 'overworld' ),
				'parent'      => $quote_post_format_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_quote_author_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Quote Author', 'overworld' ),
				'description' => esc_html__( 'Enter Quote author', 'overworld' ),
				'parent'      => $quote_post_format_meta_box
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_post_quote_meta', 25 );
}