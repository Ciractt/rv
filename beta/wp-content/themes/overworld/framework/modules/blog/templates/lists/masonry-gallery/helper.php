<?php

if ( ! function_exists( 'overworld_edge_get_blog_holder_params' ) ) {
	/**
	 * Function that generates params for holders on blog templates
	 */
	function overworld_edge_get_blog_holder_params( $params ) {
		$params_list = array();
		
		$masonry_layout = overworld_edge_get_meta_field_intersect( 'blog_masonry_layout' );
		if ( $masonry_layout === 'in-grid' ) {
			$params_list['holder'] = 'edgtf-container';
			$params_list['inner']  = 'edgtf-container-inner clearfix';
		} else {
			$params_list['holder'] = 'edgtf-full-width';
			$params_list['inner']  = 'edgtf-full-width-inner';
		}
		
		return $params_list;
	}
	
	add_filter( 'overworld_edge_filter_blog_holder_params', 'overworld_edge_get_blog_holder_params' );
}

if ( ! function_exists( 'overworld_edge_get_blog_list_classes' ) ) {
	/**
	 * Function that generates blog list holder classes for blog list templates
	 */
	function overworld_edge_get_blog_list_classes( $classes ) {
		$list_classes   = array();
		$list_classes[] = 'edgtf-grid-list edgtf-grid-masonry-list';
		
		$number_of_columns = overworld_edge_get_meta_field_intersect( 'blog_masonry_number_of_columns' );
		if ( ! empty( $number_of_columns ) ) {
			$list_classes[] = 'edgtf-' . $number_of_columns . '-columns';
		}
		
		$space_between_items = overworld_edge_get_meta_field_intersect( 'blog_masonry_space_between_items' );
		if ( ! empty( $space_between_items ) ) {
			$list_classes[] = 'edgtf-' . $space_between_items . '-space';
		}

		$enable_side_space = overworld_edge_get_meta_field_intersect( 'blog_masonry_enable_side_space' );
		if ( ! empty( $enable_side_space ) && $enable_side_space === 'yes' ) {
			$list_classes[] = 'edgtf-enable-side-space';
		}
		
		$masonry_layout = overworld_edge_get_meta_field_intersect( 'blog_masonry_layout' );
		$list_classes[] = 'edgtf-blog-masonry-' . $masonry_layout;
		
		$masonry_type = overworld_edge_get_meta_field_intersect( 'blog_list_featured_image_proportion' );
		if ( ! empty( $masonry_type ) ) {
			$list_classes[] = 'edgtf-'.$masonry_type.'-masonry-items';
		}
		
		$classes = array_merge( $classes, $list_classes );
		
		return $classes;
	}
	
	add_filter( 'overworld_edge_filter_blog_list_classes', 'overworld_edge_get_blog_list_classes' );
}

if ( ! function_exists( 'overworld_edge_blog_part_params' ) ) {
	function overworld_edge_blog_part_params( $params ) {
		$part_params              = array();
		$part_params['title_tag'] = 'h3';
		$part_params['link_tag']  = 'h4';
		$part_params['quote_tag'] = 'h4';
		$part_params['additional_params'] = array('icon_position' => 'left');

		return array_merge( $params, $part_params );
	}
	
	add_filter( 'overworld_edge_filter_blog_part_params', 'overworld_edge_blog_part_params' );
}