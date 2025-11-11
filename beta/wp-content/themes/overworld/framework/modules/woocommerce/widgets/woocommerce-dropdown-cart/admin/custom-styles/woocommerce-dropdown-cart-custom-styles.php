<?php

if ( ! function_exists( 'overworld_edge_dropdown_cart_icon_styles' ) ) {
	/**
	 * Generates styles for dropdown cart icon
	 */
	function overworld_edge_dropdown_cart_icon_styles() {
		$icon_color       = overworld_edge_options()->getOptionValue( 'dropdown_cart_icon_color' );
		$icon_hover_color = overworld_edge_options()->getOptionValue( 'dropdown_cart_hover_color' );
		
		if ( ! empty( $icon_color ) ) {
			echo overworld_edge_dynamic_css( '.edgtf-shopping-cart-holder .edgtf-header-cart a', array( 'color' => $icon_color ) );
		}
		
		if ( ! empty( $icon_hover_color ) ) {
			echo overworld_edge_dynamic_css( '.edgtf-shopping-cart-holder .edgtf-header-cart a:hover', array( 'color' => $icon_hover_color ) );
		}
	}
	
	add_action( 'overworld_edge_action_style_dynamic', 'overworld_edge_dropdown_cart_icon_styles' );
}