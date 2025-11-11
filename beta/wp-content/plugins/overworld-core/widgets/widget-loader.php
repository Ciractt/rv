<?php

if ( ! function_exists( 'overworld_core_register_widgets' ) ) {
	function overworld_core_register_widgets() {
		$widgets = apply_filters( 'overworld_core_filter_register_widgets', $widgets = array() );
		
		foreach ( $widgets as $widget ) {
			register_widget( $widget );
		}
	}
	
	add_action( 'widgets_init', 'overworld_core_register_widgets' );
}