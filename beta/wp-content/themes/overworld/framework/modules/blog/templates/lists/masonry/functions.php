<?php

if ( ! function_exists( 'overworld_edge_register_blog_masonry_template_file' ) ) {
	/**
	 * Function that register blog masonry template
	 */
	function overworld_edge_register_blog_masonry_template_file( $templates ) {
		$templates['blog-masonry'] = esc_html__( 'Blog: Masonry', 'overworld' );
		
		return $templates;
	}
	
	add_filter( 'overworld_edge_filter_register_blog_templates', 'overworld_edge_register_blog_masonry_template_file' );
}

if ( ! function_exists( 'overworld_edge_set_blog_masonry_type_global_option' ) ) {
	/**
	 * Function that set blog list type value for global blog option map
	 */
	function overworld_edge_set_blog_masonry_type_global_option( $options ) {
		$options['masonry'] = esc_html__( 'Blog: Masonry', 'overworld' );
		
		return $options;
	}
	
	add_filter( 'overworld_edge_filter_blog_list_type_global_option', 'overworld_edge_set_blog_masonry_type_global_option' );
}