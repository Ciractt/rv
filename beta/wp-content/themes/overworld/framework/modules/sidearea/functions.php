<?php

if ( ! function_exists( 'overworld_edge_register_side_area_sidebar' ) ) {
	/**
	 * Register side area sidebar
	 */
	function overworld_edge_register_side_area_sidebar() {
		register_sidebar(
			array(
				'id'            => 'sidearea',
				'name'          => esc_html__( 'Side Area', 'overworld' ),
				'description'   => esc_html__( 'Side Area', 'overworld' ),
				'before_widget' => '<div id="%1$s" class="widget edgtf-sidearea %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="edgtf-widget-title-holder"><h5 class="edgtf-widget-title">',
				'after_title'   => '</h5></div>'
			)
		);
	}
	
	add_action( 'widgets_init', 'overworld_edge_register_side_area_sidebar' );
}

if ( ! function_exists( 'overworld_edge_side_menu_body_class' ) ) {
	/**
	 * Function that adds body classes for different side menu styles
	 *
	 * @param $classes array original array of body classes
	 *
	 * @return array modified array of classes
	 */
	function overworld_edge_side_menu_body_class( $classes ) {
		
		if ( is_active_widget( false, false, 'edgtf_side_area_opener' ) ) {
			
			if ( overworld_edge_options()->getOptionValue( 'side_area_type' ) ) {
				$classes[] = 'edgtf-' . overworld_edge_options()->getOptionValue( 'side_area_type' );
			}
		}
		
		return $classes;
	}
	
	add_filter( 'body_class', 'overworld_edge_side_menu_body_class' );
}

if ( ! function_exists( 'overworld_edge_get_side_area' ) ) {
	/**
	 * Loads side area HTML
	 */
	function overworld_edge_get_side_area() {
		
		if ( is_active_widget( false, false, 'edgtf_side_area_opener' ) ) {
			overworld_edge_get_module_template_part( 'templates/sidearea', 'sidearea', '', array() );
		}
	}
	
	add_action( 'overworld_edge_action_before_closing_body_tag', 'overworld_edge_get_side_area', 10 );
}