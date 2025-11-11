<?php

if ( ! function_exists( 'overworld_edge_register_blog_list_widget' ) ) {
	/**
	 * Function that register blog list widget
	 */
	function overworld_edge_register_blog_list_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassBlogListWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_blog_list_widget' );
}