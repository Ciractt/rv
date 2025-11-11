<?php

foreach ( glob( OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT_DIR . '/title/types/*/admin/custom-styles/*.php' ) as $options_load ) {
	include_once $options_load;
}

if ( ! function_exists( 'overworld_edge_title_area_typography_style' ) ) {
	function overworld_edge_title_area_typography_style() {
		
		// title default/small style

		$item_styles = overworld_edge_get_typography_styles( 'page_title' );
		
		$item_selector = array(
			'.edgtf-title-holder .edgtf-title-wrapper .edgtf-page-title'
		);
		
		echo overworld_edge_dynamic_css( $item_selector, $item_styles );
		
		// subtitle style
		
		$item_styles = overworld_edge_get_typography_styles( 'page_subtitle' );
		
		$item_selector = array(
			'.edgtf-title-holder .edgtf-title-wrapper .edgtf-page-subtitle'
		);
		
		echo overworld_edge_dynamic_css( $item_selector, $item_styles );
	}
	
	add_action( 'overworld_edge_action_style_dynamic', 'overworld_edge_title_area_typography_style' );
}


if ( ! function_exists( 'overworld_edge_page_title_area_mobile_style' ) ) {
	function overworld_edge_page_title_area_mobile_style($style) {

		$current_style = '';
		$page_id       = overworld_edge_get_page_id();
		$class_prefix  = overworld_edge_get_unique_page_class( $page_id );

		$res_start = '@media only screen and (max-width: 1024px) {';
		$res_end   = '}';
		$item_styles   = array();

		$title_responsive_width = get_post_meta( $page_id, 'edgtf_title_area_height_mobile_meta', true );
		
		
		$item_selector = array(
			$class_prefix . ' .edgtf-title-holder',
			$class_prefix . ' .edgtf-title-holder .edgtf-title-wrapper'
		);

		if ( $title_responsive_width !== '' ) {
			$item_styles['height'] = overworld_edge_filter_suffix( $title_responsive_width, 'px') . 'px !important' ;
		}

		if(!empty($item_styles)) {
			$current_style .= $res_start . overworld_edge_dynamic_css( $item_selector, $item_styles ) . $res_end;
		}

		$current_style = $current_style . $style;

		return $current_style;
	}

	add_filter( 'overworld_edge_filter_add_page_custom_style', 'overworld_edge_page_title_area_mobile_style' );
}

if ( ! function_exists( 'overworld_edge_title_separator_style' ) ) {
	function overworld_edge_title_separator_style() {

		$item_styles = array();
		$color       = overworld_edge_options()->getOptionValue( 'title_separator_background_color' );
		if ( $color ) {
			$item_styles['background-color'] = $color;
		}

		$item_selector = array(
			'.edgtf-title-holder.edgtf-title-with-separator .edgtf-title-info::before'
		);

		if ( ! empty( $item_styles ) ) {
			echo overworld_edge_dynamic_css( $item_selector, $item_styles );
		}
	}

	add_action( 'overworld_edge_action_style_dynamic', 'overworld_edge_title_separator_style' );
}

if ( ! function_exists( 'overworld_edge_page_title_separator_style' ) ) {
	function overworld_edge_page_title_separator_style( $style ) {

		$current_style = '';
		$page_id       = overworld_edge_get_page_id();
		$class_prefix  = overworld_edge_get_unique_page_class( $page_id );

		$item_styles = array();

		$title_separator_color = get_post_meta( $page_id, 'edgtf_title_separator_background_color_meta', true );

		$item_selector = array(
			$class_prefix . ' .edgtf-title-holder.edgtf-title-with-separator .edgtf-title-info::before'
		);

		if ( $title_separator_color !== '' ) {
			$item_styles['background-color'] = $title_separator_color;
		}

		if ( ! empty( $item_styles ) ) {
			$current_style .= overworld_edge_dynamic_css( $item_selector, $item_styles );
		}

		$current_style = $current_style . $style;

		return $current_style;
	}

	add_filter( 'overworld_edge_filter_add_page_custom_style', 'overworld_edge_page_title_separator_style' );
}