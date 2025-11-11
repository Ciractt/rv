<?php

if ( ! function_exists( 'overworld_edge_map_post_audio_meta' ) ) {
	function overworld_edge_map_post_audio_meta() {
		$audio_post_format_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'post' ),
				'title' => esc_html__( 'Audio Post Format', 'overworld' ),
				'name'  => 'post_format_audio_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_audio_type_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Audio Type', 'overworld' ),
				'description'   => esc_html__( 'Choose audio type', 'overworld' ),
				'parent'        => $audio_post_format_meta_box,
				'default_value' => 'social_networks',
				'options'       => array(
					'social_networks' => esc_html__( 'Audio Service', 'overworld' ),
					'self'            => esc_html__( 'Self Hosted', 'overworld' )
				)
			)
		);
		
		$edgtf_audio_embedded_container = overworld_edge_add_admin_container(
			array(
				'parent' => $audio_post_format_meta_box,
				'name'   => 'edgtf_audio_embedded_container'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_audio_link_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Audio URL', 'overworld' ),
				'description' => esc_html__( 'Enter audio URL', 'overworld' ),
				'parent'      => $edgtf_audio_embedded_container,
				'dependency' => array(
					'show' => array(
						'edgtf_audio_type_meta' => 'social_networks'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_post_audio_custom_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Audio Link', 'overworld' ),
				'description' => esc_html__( 'Enter audio link', 'overworld' ),
				'parent'      => $edgtf_audio_embedded_container,
				'dependency' => array(
					'show' => array(
						'edgtf_audio_type_meta' => 'self'
					)
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_post_audio_meta', 23 );
}