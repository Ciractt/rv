<?php

if ( ! function_exists( 'overworld_edge_map_post_gallery_meta' ) ) {
	
	function overworld_edge_map_post_gallery_meta() {
		$gallery_post_format_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'post' ),
				'title' => esc_html__( 'Gallery Post Format', 'overworld' ),
				'name'  => 'post_format_gallery_meta'
			)
		);
		
		overworld_edge_add_multiple_images_field(
			array(
				'name'        => 'edgtf_post_gallery_images_meta',
				'label'       => esc_html__( 'Gallery Images', 'overworld' ),
				'description' => esc_html__( 'Choose your gallery images', 'overworld' ),
				'parent'      => $gallery_post_format_meta_box,
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_post_gallery_meta', 21 );
}
