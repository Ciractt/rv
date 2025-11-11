<?php

if ( ! function_exists( 'overworld_core_add_dropcaps_shortcodes' ) ) {
	function overworld_core_add_dropcaps_shortcodes( $shortcodes_class_name ) {
		$shortcodes = array(
			'OverworldCore\CPT\Shortcodes\Dropcaps\Dropcaps'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcode', 'overworld_core_add_dropcaps_shortcodes' );
}