<?php
if ( ! function_exists( 'overworld_core_add_single_match_simple_shortcode' ) ) {
	function overworld_core_add_single_match_simple_shortcode( $shortcodes_class_name ) {
		$shortcodes = array(
			'OverworldCore\CPT\Shortcodes\Match\SingleMatchSimple'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcode', 'overworld_core_add_single_match_simple_shortcode' );
}

if ( ! function_exists( 'overworld_core_set_single_match_simple_icon_class_name_for_vc_shortcodes' ) ) {
	/**
	 * Function that set custom icon class name for single match simple shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function overworld_core_set_single_match_simple_icon_class_name_for_vc_shortcodes( $shortcodes_icon_class_array ) {
		$shortcodes_icon_class_array[] = '.icon-wpb-single-match-simple';
		
		return $shortcodes_icon_class_array;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcodes_custom_icon_class', 'overworld_core_set_single_match_simple_icon_class_name_for_vc_shortcodes' );
}