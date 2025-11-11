<?php

if ( ! function_exists( 'overworld_edge_include_blog_shortcodes' ) ) {
	function overworld_edge_include_blog_shortcodes() {
		foreach ( glob( OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT_DIR . '/blog/shortcodes/*/load.php' ) as $shortcode_load ) {
			include_once $shortcode_load;
		}
	}
	
	if ( overworld_edge_is_plugin_installed( 'core' ) ) {
		add_action( 'overworld_core_action_include_shortcodes_file', 'overworld_edge_include_blog_shortcodes' );
	}
}
