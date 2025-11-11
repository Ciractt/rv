<?php

if ( overworld_edge_is_plugin_installed( 'contact-form-7' ) ) {
	include_once OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT_DIR . '/widgets/contact-form-7/contact-form-7.php';
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_cf7_widget' );
}

if ( ! function_exists( 'overworld_edge_register_cf7_widget' ) ) {
	/**
	 * Function that register cf7 widget
	 */
	function overworld_edge_register_cf7_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassContactForm7Widget';
		
		return $widgets;
	}
}