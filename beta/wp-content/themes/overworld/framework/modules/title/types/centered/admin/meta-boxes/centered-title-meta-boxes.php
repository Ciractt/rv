<?php

if ( ! function_exists( 'overworld_edge_centered_title_type_options_meta_boxes' ) ) {
	function overworld_edge_centered_title_type_options_meta_boxes( $show_title_area_meta_container ) {
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_subtitle_side_padding_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Subtitle Side Padding', 'overworld' ),
				'description' => esc_html__( 'Set left/right padding for subtitle area', 'overworld' ),
				'parent'      => $show_title_area_meta_container,
				'args'        => array(
					'col_width' => 2,
					'suffix'    => 'px or %'
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_title_area_meta_boxes', 'overworld_edge_centered_title_type_options_meta_boxes', 5 );
}