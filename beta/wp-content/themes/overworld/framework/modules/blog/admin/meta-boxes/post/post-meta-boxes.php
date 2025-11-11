<?php

/*** Post Settings ***/

if ( ! function_exists( 'overworld_edge_map_post_meta' ) ) {
	function overworld_edge_map_post_meta() {
		
		$post_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'post' ),
				'title' => esc_html__( 'Post', 'overworld' ),
				'name'  => 'post-meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_show_title_area_blog_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Show Title Area', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will show title area on your single post page', 'overworld' ),
				'parent'        => $post_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_blog_single_title_hide_title_content_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Hide Post Title Content in Title Area', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will hide post title content in title area on single post pages', 'overworld' ),
				'parent'        => $post_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array(),
				'dependency' => array(
					'hide' => array(
						'edgtf_show_title_area_blog_meta' => 'no'
					)
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_blog_single_sidebar_layout_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Sidebar Layout', 'overworld' ),
				'description'   => esc_html__( 'Choose a sidebar layout for Blog single page', 'overworld' ),
				'default_value' => '',
				'parent'        => $post_meta_box,
                'options'       => overworld_edge_get_custom_sidebars_options( true )
			)
		);
		
		$overworld_custom_sidebars = overworld_edge_get_custom_sidebars();
		if ( count( $overworld_custom_sidebars ) > 0 ) {
			overworld_edge_create_meta_box_field( array(
				'name'        => 'edgtf_blog_single_custom_sidebar_area_meta',
				'type'        => 'selectblank',
				'label'       => esc_html__( 'Sidebar to Display', 'overworld' ),
				'description' => esc_html__( 'Choose a sidebar to display on Blog single page. Default sidebar is "Sidebar"', 'overworld' ),
				'parent'      => $post_meta_box,
				'options'     => overworld_edge_get_custom_sidebars(),
				'args' => array(
					'select2' => true
				)
			) );
		}
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_blog_list_featured_image_meta',
				'type'        => 'image',
				'label'       => esc_html__( 'Blog List Image', 'overworld' ),
				'description' => esc_html__( 'Choose an Image for displaying in blog list. If not uploaded, featured image will be shown.', 'overworld' ),
				'parent'      => $post_meta_box
			)
		);

		do_action('overworld_edge_action_blog_post_meta', $post_meta_box);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_post_meta', 20 );
}
