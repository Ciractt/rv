<?php

if ( ! function_exists( 'overworld_edge_map_post_video_meta' ) ) {
	function overworld_edge_map_post_video_meta() {
		$video_post_format_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'post' ),
				'title' => esc_html__( 'Video Post Format', 'overworld' ),
				'name'  => 'post_format_video_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_video_type_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Video Type', 'overworld' ),
				'description'   => esc_html__( 'Choose video type', 'overworld' ),
				'parent'        => $video_post_format_meta_box,
				'default_value' => 'social_networks',
				'options'       => array(
					'social_networks' => esc_html__( 'Video Service', 'overworld' ),
					'self'            => esc_html__( 'Self Hosted', 'overworld' )
				)
			)
		);
		
		$edgtf_video_embedded_container = overworld_edge_add_admin_container(
			array(
				'parent' => $video_post_format_meta_box,
				'name'   => 'edgtf_video_embedded_container'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_link_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Video URL', 'overworld' ),
				'description' => esc_html__( 'Enter Video URL', 'overworld' ),
				'parent'      => $edgtf_video_embedded_container,
				'dependency' => array(
					'show' => array(
						'edgtf_video_type_meta' => 'social_networks'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_custom_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Video MP4', 'overworld' ),
				'description' => esc_html__( 'Enter video URL for MP4 format', 'overworld' ),
				'parent'      => $edgtf_video_embedded_container,
				'dependency' => array(
					'show' => array(
						'edgtf_video_type_meta' => 'self'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_image_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Video Image', 'overworld' ),
				'description' => esc_html__( 'Enter video image', 'overworld' ),
				'parent'      => $edgtf_video_embedded_container,
				'dependency' => array(
					'show' => array(
						'edgtf_video_type_meta' => 'self'
					)
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_post_video_meta', 22 );
}