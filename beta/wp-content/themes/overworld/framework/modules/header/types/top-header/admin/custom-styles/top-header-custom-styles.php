<?php

if ( ! function_exists( 'overworld_edge_header_top_bar_styles' ) ) {
	/**
	 * Generates styles for header top bar
	 */
	function overworld_edge_header_top_bar_styles() {
		$top_header_height = overworld_edge_options()->getOptionValue( 'top_bar_height' );
		
		if ( ! empty( $top_header_height ) ) {
			echo overworld_edge_dynamic_css( '.edgtf-top-bar', array( 'height' => overworld_edge_filter_px( $top_header_height ) . 'px' ) );
			echo overworld_edge_dynamic_css( '.edgtf-top-bar .edgtf-logo-wrapper a', array( 'max-height' => overworld_edge_filter_px( $top_header_height ) . 'px' ) );
		}
		
		echo overworld_edge_dynamic_css( '.edgtf-header-box .edgtf-top-bar-background', array( 'height' => overworld_edge_get_top_bar_background_height() . 'px' ) );
		
		$top_bar_container_selector = '.edgtf-top-bar > .edgtf-vertical-align-containers';
		$top_bar_container_styles   = array();
		$container_side_padding     = overworld_edge_options()->getOptionValue( 'top_bar_side_padding' );
		
		if ( $container_side_padding !== '' ) {
			if ( overworld_edge_string_ends_with( $container_side_padding, 'px' ) || overworld_edge_string_ends_with( $container_side_padding, '%' ) ) {
				$top_bar_container_styles['padding-left'] = $container_side_padding;
				$top_bar_container_styles['padding-right'] = $container_side_padding;
			} else {
				$top_bar_container_styles['padding-left'] = overworld_edge_filter_px( $container_side_padding ) . 'px';
				$top_bar_container_styles['padding-right'] = overworld_edge_filter_px( $container_side_padding ) . 'px';
			}
			
			echo overworld_edge_dynamic_css( $top_bar_container_selector, $top_bar_container_styles );
		}
		
		if ( overworld_edge_options()->getOptionValue( 'top_bar_in_grid' ) == 'yes' ) {
			$top_bar_grid_selector                = '.edgtf-top-bar .edgtf-grid .edgtf-vertical-align-containers';
			$top_bar_grid_styles                  = array();
			$top_bar_grid_background_color        = overworld_edge_options()->getOptionValue( 'top_bar_grid_background_color' );
			$top_bar_grid_background_transparency = overworld_edge_options()->getOptionValue( 'top_bar_grid_background_transparency' );
			
			if ( !empty($top_bar_grid_background_color) ) {
				$grid_background_color        = $top_bar_grid_background_color;
				$grid_background_transparency = 1;
				
				if ( $top_bar_grid_background_transparency !== '' ) {
					$grid_background_transparency = $top_bar_grid_background_transparency;
				}
				
				$grid_background_color                   = overworld_edge_rgba_color( $grid_background_color, $grid_background_transparency );
				$top_bar_grid_styles['background-color'] = $grid_background_color;
			}
			
			echo overworld_edge_dynamic_css( $top_bar_grid_selector, $top_bar_grid_styles );
		}
		
		$top_bar_styles   = array();
		$background_color = overworld_edge_options()->getOptionValue( 'top_bar_background_color' );
		$border_color     = overworld_edge_options()->getOptionValue( 'top_bar_border_color' );
		
		if ( $background_color !== '' ) {
			$background_transparency = 1;
			if ( overworld_edge_options()->getOptionValue( 'top_bar_background_transparency' ) !== '' ) {
				$background_transparency = overworld_edge_options()->getOptionValue( 'top_bar_background_transparency' );
			}
			
			$background_color                   = overworld_edge_rgba_color( $background_color, $background_transparency );
			$top_bar_styles['background-color'] = $background_color;
			
			echo overworld_edge_dynamic_css( '.edgtf-header-box .edgtf-top-bar-background', array( 'background-color' => $background_color ) );
		}
		
		if ( overworld_edge_options()->getOptionValue( 'top_bar_border' ) == 'yes' && $border_color != '' ) {
			$top_bar_styles['border-bottom'] = '1px solid ' . $border_color;
		}
		
		echo overworld_edge_dynamic_css( '.edgtf-top-bar', $top_bar_styles );
	}
	
	add_action( 'overworld_edge_action_style_dynamic', 'overworld_edge_header_top_bar_styles' );
}