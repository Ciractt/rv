<?php

if ( ! function_exists( 'overworld_core_load_widget_class' ) ) {
	/**
	 * Loades widget class file.
	 */
	function overworld_core_load_widget_class() {
		include_once 'widget-class.php';
	}
	
	add_action( 'overworld_edge_action_before_options_map', 'overworld_core_load_widget_class' );
}

if ( ! function_exists( 'overworld_core_load_widgets' ) ) {
	/**
	 * Loades all widgets by going through all folders that are placed directly in widgets folder
	 * and loads load.php file in each. Hooks to overworld_edge_action_after_options_map action
	 */
	function overworld_core_load_widgets() {
		
		if ( overworld_core_theme_installed() ) {
			foreach ( glob( OVERWORLD_EDGE_FRAMEWORK_ROOT_DIR . '/modules/widgets/*/load.php' ) as $widget_load ) {
				include_once $widget_load;
			}
		}
		
		include_once 'widget-loader.php';
	}
	
	add_action( 'overworld_edge_action_before_options_map', 'overworld_core_load_widgets' );
}