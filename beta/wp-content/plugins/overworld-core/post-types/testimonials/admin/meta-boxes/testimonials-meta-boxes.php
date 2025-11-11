<?php

if ( ! function_exists( 'overworld_core_map_testimonials_meta' ) ) {
	function overworld_core_map_testimonials_meta() {
		$testimonial_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'testimonials' ),
				'title' => esc_html__( 'Testimonial', 'overworld-core' ),
				'name'  => 'testimonial_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_testimonial_title',
				'type'        => 'text',
				'label'       => esc_html__( 'Title', 'overworld-core' ),
				'description' => esc_html__( 'Enter testimonial title', 'overworld-core' ),
				'parent'      => $testimonial_meta_box,
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_testimonial_text',
				'type'        => 'text',
				'label'       => esc_html__( 'Text', 'overworld-core' ),
				'description' => esc_html__( 'Enter testimonial text', 'overworld-core' ),
				'parent'      => $testimonial_meta_box,
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_testimonial_author',
				'type'        => 'text',
				'label'       => esc_html__( 'Author', 'overworld-core' ),
				'description' => esc_html__( 'Enter author name', 'overworld-core' ),
				'parent'      => $testimonial_meta_box,
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_testimonial_author_position',
				'type'        => 'text',
				'label'       => esc_html__( 'Author Position', 'overworld-core' ),
				'description' => esc_html__( 'Enter author job position', 'overworld-core' ),
				'parent'      => $testimonial_meta_box,
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_core_map_testimonials_meta', 95 );
}