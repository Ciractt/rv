<?php

if ( ! function_exists( 'overworld_edge_get_section_title_html' ) ) {
	/**
	 * Calls section title shortcode with given parameters and returns it's output
	 *
	 * @param $params
	 *
	 * @return mixed|string
	 */
	function overworld_edge_get_section_title_html( $params ) {
		$section_title_html = overworld_edge_execute_shortcode( 'edgtf_section_title', $params );
		$section_title_html = str_replace( "\n", '', $section_title_html );

		return $section_title_html;
	}
}

if ( ! function_exists( 'overworld_core_add_section_title_shortcodes' ) ) {
	function overworld_core_add_section_title_shortcodes( $shortcodes_class_name ) {
		$shortcodes = array(
			'OverworldCore\CPT\Shortcodes\SectionTitle\SectionTitle'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcode', 'overworld_core_add_section_title_shortcodes' );
}

if ( ! function_exists( 'overworld_core_set_section_title_icon_class_name_for_vc_shortcodes' ) ) {
	/**
	 * Function that set custom icon class name for section title shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function overworld_core_set_section_title_icon_class_name_for_vc_shortcodes( $shortcodes_icon_class_array ) {
		$shortcodes_icon_class_array[] = '.icon-wpb-section-title';
		
		return $shortcodes_icon_class_array;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcodes_custom_icon_class', 'overworld_core_set_section_title_icon_class_name_for_vc_shortcodes' );
}