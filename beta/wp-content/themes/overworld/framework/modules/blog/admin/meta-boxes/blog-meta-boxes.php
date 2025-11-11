<?php

foreach ( glob( OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT_DIR . '/blog/admin/meta-boxes/*/*.php' ) as $meta_box_load ) {
	include_once $meta_box_load;
}

if ( ! function_exists( 'overworld_edge_map_blog_meta' ) ) {
	function overworld_edge_map_blog_meta() {
		$edgtf_blog_categories = array();
		$categories           = get_categories();
		foreach ( $categories as $category ) {
			$edgtf_blog_categories[ $category->slug ] = $category->name;
		}
		
		$blog_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'page' ),
				'title' => esc_html__( 'Blog', 'overworld' ),
				'name'  => 'blog_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_blog_category_meta',
				'type'        => 'selectblank',
				'label'       => esc_html__( 'Blog Category', 'overworld' ),
				'description' => esc_html__( 'Choose category of posts to display (leave empty to display all categories)', 'overworld' ),
				'parent'      => $blog_meta_box,
				'options'     => $edgtf_blog_categories
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_show_posts_per_page_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Number of Posts', 'overworld' ),
				'description' => esc_html__( 'Enter the number of posts to display', 'overworld' ),
				'parent'      => $blog_meta_box,
				'options'     => $edgtf_blog_categories,
				'args'        => array(
					'col_width' => 3
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_blog_masonry_layout_meta',
				'type'        => 'select',
				'label'       => esc_html__( 'Masonry - Layout', 'overworld' ),
				'description' => esc_html__( 'Set masonry layout. Default is in grid.', 'overworld' ),
				'parent'      => $blog_meta_box,
				'options'     => array(
					''           => esc_html__( 'Default', 'overworld' ),
					'in-grid'    => esc_html__( 'In Grid', 'overworld' ),
					'full-width' => esc_html__( 'Full Width', 'overworld' )
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_blog_masonry_number_of_columns_meta',
				'type'        => 'select',
				'label'       => esc_html__( 'Masonry - Number of Columns', 'overworld' ),
				'description' => esc_html__( 'Set number of columns for your masonry blog lists', 'overworld' ),
				'parent'      => $blog_meta_box,
				'options'     => overworld_edge_get_number_of_columns_array( true, array( 'one', 'six' ) )
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_blog_masonry_space_between_items_meta',
				'type'        => 'select',
				'label'       => esc_html__( 'Masonry - Space Between Items', 'overworld' ),
				'description' => esc_html__( 'Set space size between posts for your masonry blog lists', 'overworld' ),
				'options'     => overworld_edge_get_space_between_items_array( true ),
				'parent'      => $blog_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_blog_masonry_enable_side_space_meta',
				'type'        => 'select',
				'label'       => esc_html__( 'Enable Side Space', 'overworld' ),
				'description' => esc_html__( 'Enable side space for your masonry blog lists', 'overworld' ),
				'options'       => array(
					''    => esc_html__( 'Default', 'overworld' ),
					'no'  => esc_html__( 'No', 'overworld' ),
					'yes' => esc_html__( 'Yes', 'overworld' )
				),
				'parent'      => $blog_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_blog_list_featured_image_proportion_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Masonry - Featured Image Proportion', 'overworld' ),
				'description'   => esc_html__( 'Choose type of proportions you want to use for featured images on masonry blog lists', 'overworld' ),
				'parent'        => $blog_meta_box,
				'default_value' => '',
				'options'       => array(
					''         => esc_html__( 'Default', 'overworld' ),
					'fixed'    => esc_html__( 'Fixed', 'overworld' ),
					'original' => esc_html__( 'Original', 'overworld' )
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_blog_pagination_type_meta',
				'type'          => 'select',
				'label'         => esc_html__( 'Pagination Type', 'overworld' ),
				'description'   => esc_html__( 'Choose a pagination layout for Blog Lists', 'overworld' ),
				'parent'        => $blog_meta_box,
				'default_value' => '',
				'options'       => array(
					''                => esc_html__( 'Default', 'overworld' ),
					'standard'        => esc_html__( 'Standard', 'overworld' ),
					'load-more'       => esc_html__( 'Load More', 'overworld' ),
					'infinite-scroll' => esc_html__( 'Infinite Scroll', 'overworld' ),
					'no-pagination'   => esc_html__( 'No Pagination', 'overworld' )
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'type'          => 'text',
				'name'          => 'edgtf_number_of_chars_meta',
				'default_value' => '',
				'label'         => esc_html__( 'Number of Words in Excerpt (Standard List Only)', 'overworld' ),
				'description'   => esc_html__( 'Enter a number of words in excerpt (article summary). Default value is 40', 'overworld' ),
				'parent'        => $blog_meta_box,
				'args'          => array(
					'col_width' => 3
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_blog_meta', 30 );
}