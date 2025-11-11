<?php

if ( ! function_exists( 'overworld_edge_register_woocommerce_dropdown_cart_widget' ) ) {
	/**
	 * Function that register dropdown cart widget
	 */
	function overworld_edge_register_woocommerce_dropdown_cart_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassWoocommerceDropdownCart';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_woocommerce_dropdown_cart_widget' );
}

if ( ! function_exists( 'overworld_edge_get_dropdown_cart_icon_class' ) ) {
	/**
	 * Returns dropdow cart icon class
	 */
	function overworld_edge_get_dropdown_cart_icon_class() {
		$classes = array(
			'edgtf-header-cart'
		);
		
		$classes[] = overworld_edge_get_icon_sources_class( 'dropdown_cart', 'edgtf-header-cart' );
		
		return implode( ' ', $classes );
	}
}