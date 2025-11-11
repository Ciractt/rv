<?php

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Edgtf_Image_Showcase extends WPBakeryShortCodesContainer {}
}

if ( ! function_exists( 'overworld_core_add_image_showcase_shortcodes' ) ) {
	function overworld_core_add_image_showcase_shortcodes( $shortcodes_class_name ) {
		$shortcodes = array(
			'OverworldCore\CPT\Shortcodes\ImageShowcase\ImageShowcase'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcode', 'overworld_core_add_image_showcase_shortcodes' );
}

if ( ! function_exists( 'overworld_core_set_image_showcase_custom_style_for_vc_shortcodes' ) ) {
	/**
	 * Function that set custom css style for image showcase shortcode
	 */
	function overworld_core_set_image_showcase_custom_style_for_vc_shortcodes( $style ) {
		$current_style = '.vc_shortcodes_container.wpb_edgtf_image_showcase {
			background-color: #f4f4f4; 
		}';

		$style .= $current_style;

		return $style;
	}

	add_filter( 'overworld_core_filter_add_vc_shortcodes_custom_style', 'overworld_core_set_image_showcase_custom_style_for_vc_shortcodes' );
}

if ( ! function_exists( 'overworld_core_set_image_showcase_icon_class_name_for_vc_shortcodes' ) ) {
	/**
	 * Function that set custom icon class name for image showcase shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function overworld_core_set_image_showcase_icon_class_name_for_vc_shortcodes( $shortcodes_icon_class_array ) {
		$shortcodes_icon_class_array[] = '.icon-wpb-image-showcase';
		
		return $shortcodes_icon_class_array;
	}
	
	add_filter( 'overworld_core_filter_add_vc_shortcodes_custom_icon_class', 'overworld_core_set_image_showcase_icon_class_name_for_vc_shortcodes' );
}