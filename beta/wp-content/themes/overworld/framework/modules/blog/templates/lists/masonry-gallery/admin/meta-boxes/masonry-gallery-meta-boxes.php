<?php

/*** Masonry Gallery Settings ***/

if ( ! function_exists( 'overworld_edge_map_masonry_gallery_meta' ) ) {
	function overworld_edge_map_masonry_gallery_meta( $post_meta_box ) {
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_blog_masonry_gallery_fixed_dimensions_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Masonry Gallery List - Dimensions for Fixed Proportion', 'overworld' ),
				'description'   => esc_html__( 'Choose image layout when it appears in Masonry lists in fixed proportion', 'overworld' ),
				'default_value' => '',
				'parent'        => $post_meta_box,
				'options'       => array(
					''                   => esc_html__( 'Default', 'overworld' ),
					'small'              => esc_html__( 'Small', 'overworld' ),
					'large-width'        => esc_html__( 'Large Width', 'overworld' ),
					'large-height'       => esc_html__( 'Large Height', 'overworld' ),
					'large-width-height' => esc_html__( 'Large Width/Height', 'overworld' )
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_blog_masonry_gallery_original_dimensions_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Masonry Gallery List - Dimensions for Original Proportion', 'overworld' ),
				'description'   => esc_html__( 'Choose image layout when it appears in Masonry lists in original proportion', 'overworld' ),
				'default_value' => 'default',
				'parent'        => $post_meta_box,
				'options'       => array(
					'default'     => esc_html__( 'Default', 'overworld' ),
					'large-width' => esc_html__( 'Large Width', 'overworld' )
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_blog_post_meta', 'overworld_edge_map_masonry_gallery_meta' );
}
