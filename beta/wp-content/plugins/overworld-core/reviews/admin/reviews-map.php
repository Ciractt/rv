<?php

if ( ! function_exists( 'overworld_core_reviews_map' ) ) {
	function overworld_core_reviews_map() {
		
		$reviews_panel = overworld_edge_add_admin_panel(
			array(
				'title' => esc_html__( 'Reviews', 'overworld-core' ),
				'name'  => 'panel_reviews',
				'page'  => '_page_page'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $reviews_panel,
				'type'        => 'text',
				'name'        => 'reviews_section_title',
				'label'       => esc_html__( 'Reviews Section Title', 'overworld-core' ),
				'description' => esc_html__( 'Enter title that you want to show before average rating on your page', 'overworld-core' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $reviews_panel,
				'type'        => 'textarea',
				'name'        => 'reviews_section_subtitle',
				'label'       => esc_html__( 'Reviews Section Subtitle', 'overworld-core' ),
				'description' => esc_html__( 'Enter subtitle that you want to show before average rating on your page', 'overworld-core' ),
			)
		);
	}
	
	add_action( 'overworld_edge_action_additional_page_options_map', 'overworld_core_reviews_map', 75 ); //one after elements
}