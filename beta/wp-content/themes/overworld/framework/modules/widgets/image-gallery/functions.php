<?php

if ( ! function_exists( 'overworld_edge_register_image_gallery_widget' ) ) {
	/**
	 * Function that register image gallery widget
	 */
	function overworld_edge_register_image_gallery_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassImageGalleryWidget';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_image_gallery_widget' );
}