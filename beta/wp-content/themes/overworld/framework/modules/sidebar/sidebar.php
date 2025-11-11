<?php

if ( ! function_exists( 'overworld_edge_register_sidebars' ) ) {
	/**
	 * Function that registers theme's sidebars
	 */
	function overworld_edge_register_sidebars() {
		
		register_sidebar(
			array(
				'id'            => 'sidebar',
				'name'          => esc_html__( 'Sidebar', 'overworld' ),
				'description'   => esc_html__( 'Default Sidebar area. In order to display this area you need to enable it through global theme options or on page meta box options.', 'overworld' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="edgtf-widget-title-holder"><h3 class="edgtf-widget-title">',
				'after_title'   => '</h3></div>'
			)
		);
	}
	
	add_action( 'widgets_init', 'overworld_edge_register_sidebars', 1 );
}

if ( ! function_exists( 'overworld_edge_add_support_custom_sidebar' ) ) {
	/**
	 * Function that adds theme support for custom sidebars. It also creates OverworldEdgeClassSidebar object
	 */
	function overworld_edge_add_support_custom_sidebar() {
		add_theme_support( 'OverworldEdgeClassSidebar' );
		
		if ( get_theme_support( 'OverworldEdgeClassSidebar' ) ) {
			new OverworldEdgeClassSidebar();
		}
	}
	
	add_action( 'after_setup_theme', 'overworld_edge_add_support_custom_sidebar' );
}