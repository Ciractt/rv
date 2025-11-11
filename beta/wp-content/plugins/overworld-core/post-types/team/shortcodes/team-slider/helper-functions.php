<?php

if ( ! function_exists( 'overworld_core_add_team_slider_shortcode' ) ) {
	function overworld_core_add_team_slider_shortcode( $shortcodes_class_name ) {
		$shortcodes = array(
			'OverworldCore\CPT\Shortcodes\Team\TeamSlider'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcode', 'overworld_core_add_team_slider_shortcode' );
}

if ( ! function_exists( 'overworld_core_set_team_slider_icon_class_name_for_vc_shortcodes' ) ) {
	/**
	 * Function that set custom icon class name for team slider shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function overworld_core_set_team_slider_icon_class_name_for_vc_shortcodes( $shortcodes_icon_class_array ) {
        $shortcodes_icon_class_array[] = '.icon-wpb-team-slider';
		
		return $shortcodes_icon_class_array;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcodes_custom_icon_class', 'overworld_core_set_team_slider_icon_class_name_for_vc_shortcodes' );
}