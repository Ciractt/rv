<?php

if ( ! function_exists( 'overworld_edge_include_mobile_header_menu' ) ) {
	function overworld_edge_include_mobile_header_menu( $menus ) {
		$menus['mobile-navigation'] = esc_html__( 'Mobile Navigation', 'overworld' );
		
		return $menus;
	}
	
	add_filter( 'overworld_edge_filter_register_headers_menu', 'overworld_edge_include_mobile_header_menu' );
}

if ( ! function_exists( 'overworld_edge_register_mobile_header_areas' ) ) {
	/**
	 * Registers widget areas for mobile header
	 */
	function overworld_edge_register_mobile_header_areas() {
		if ( overworld_edge_is_responsive_on() && overworld_edge_is_plugin_installed( 'core' ) ) {
			register_sidebar(
				array(
					'id'            => 'edgtf-right-from-mobile-logo',
					'name'          => esc_html__( 'Mobile Header Widget Area', 'overworld' ),
					'description'   => esc_html__( 'Widgets added here will appear on the right hand side on mobile header', 'overworld' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-right-from-mobile-logo">',
					'after_widget'  => '</div>'
				)
			);
		}
	}
	
	add_action( 'widgets_init', 'overworld_edge_register_mobile_header_areas' );
}

if ( ! function_exists( 'overworld_edge_mobile_header_class' ) ) {
	function overworld_edge_mobile_header_class( $classes ) {
		$classes[] = 'edgtf-default-mobile-header edgtf-sticky-up-mobile-header';
		
		return $classes;
	}
	
	add_filter( 'body_class', 'overworld_edge_mobile_header_class' );
}

if ( ! function_exists( 'overworld_edge_get_mobile_header' ) ) {
	/**
	 * Loads mobile header HTML only if responsiveness is enabled
	 *
	 * @param string $slug
	 * @param string $module
	 */
	function overworld_edge_get_mobile_header( $slug = '', $module = '' ) {
		if ( overworld_edge_is_responsive_on() ) {
			$page_id           = overworld_edge_get_page_id();
			$mobile_in_grid    = overworld_edge_get_meta_field_intersect( 'mobile_header_in_grid', $page_id ) == 'yes' ? true : false;
			$mobile_menu_title = overworld_edge_options()->getOptionValue( 'mobile_menu_title' );
			$has_navigation    = has_nav_menu( 'main-navigation' ) || has_nav_menu( 'mobile-navigation' );
			
			$parameters = array(
				'mobile_header_in_grid'  => $mobile_in_grid,
				'show_navigation_opener' => $has_navigation,
				'mobile_menu_title'      => $mobile_menu_title,
				'mobile_icon_class'		 => overworld_edge_get_mobile_navigation_icon_class()
			);

            $module = apply_filters('overworld_edge_filter_mobile_menu_module', 'header/types/mobile-header');
            $slug = apply_filters('overworld_edge_filter_mobile_menu_slug', '');
            $parameters = apply_filters('overworld_edge_filter_mobile_menu_parameters', $parameters);

            overworld_edge_get_module_template_part( 'templates/mobile-header', $module, $slug, $parameters );
		}
	}
	
	add_action( 'overworld_edge_action_after_wrapper_inner', 'overworld_edge_get_mobile_header', 20 );
}

if ( ! function_exists( 'overworld_edge_get_mobile_logo' ) ) {
	/**
	 * Loads mobile logo HTML. It checks if mobile logo image is set and uses that, else takes normal logo image
	 */
	function overworld_edge_get_mobile_logo() {
		$show_logo_image = overworld_edge_options()->getOptionValue( 'hide_logo' ) === 'yes' ? false : true;
		
		if ( $show_logo_image ) {
			$page_id       = overworld_edge_get_page_id();
			$header_height = overworld_edge_set_default_mobile_menu_height_for_header_types();
			
			$mobile_logo_image = overworld_edge_get_meta_field_intersect( 'logo_image_mobile', $page_id );
			
			//check if mobile logo has been set and use that, else use normal logo
			$logo_image = ! empty( $mobile_logo_image ) ? $mobile_logo_image : overworld_edge_get_meta_field_intersect( 'logo_image', $page_id );
			
			//get logo image dimensions and set style attribute for image link.
			$logo_dimensions = overworld_edge_get_image_dimensions( $logo_image );
			
			$logo_styles = '';
			if ( is_array( $logo_dimensions ) && array_key_exists( 'height', $logo_dimensions ) ) {
				$logo_height = $logo_dimensions['height'];
				$logo_styles = 'height: ' . intval( $logo_height / 2 ) . 'px'; //divided with 2 because of retina screens
			} else if ( ! empty( $header_height ) && empty( $logo_dimensions ) ) {
				$logo_styles = 'height: ' . intval( $header_height / 2 ) . 'px;'; //divided with 2 because of retina screens
			}
			
			//set parameters for logo
			$parameters = array(
				'logo_image'      => $logo_image,
				'logo_dimensions' => $logo_dimensions,
				'logo_styles'     => $logo_styles
			);
			
			overworld_edge_get_module_template_part( 'templates/mobile-logo', 'header/types/mobile-header', '', $parameters );
		}
	}
}

if ( ! function_exists( 'overworld_edge_get_mobile_nav' ) ) {
	/**
	 * Loads mobile navigation HTML
	 */
	function overworld_edge_get_mobile_nav() {
		overworld_edge_get_module_template_part( 'templates/mobile-navigation', 'header/types/mobile-header' );
	}
}

if ( ! function_exists( 'overworld_edge_mobile_header_per_page_js_var' ) ) {
    function overworld_edge_mobile_header_per_page_js_var( $perPageVars ) {
        $perPageVars['edgtfMobileHeaderHeight'] = overworld_edge_set_default_mobile_menu_height_for_header_types();

        return $perPageVars;
    }

    add_filter( 'overworld_edge_filter_per_page_js_vars', 'overworld_edge_mobile_header_per_page_js_var' );
}

if ( ! function_exists( 'overworld_edge_get_mobile_navigation_icon_class' ) ) {
	/**
	 * Loads mobile navigation icon class
	 */
	function overworld_edge_get_mobile_navigation_icon_class() {
		$classes = array(
			'edgtf-mobile-menu-opener'
		);
		
		$classes[] = overworld_edge_get_icon_sources_class( 'mobile', 'edgtf-mobile-menu-opener' );

		return $classes;
	}
}


if ( ! function_exists( 'overworld_edge_mobile_header_style' ) ) {
	function overworld_edge_mobile_header_style($style) {

		$current_style = '';
		$page_id       = overworld_edge_get_page_id();
		$class_prefix  = overworld_edge_get_unique_page_class( $page_id );

		$mobile_side_padding    = overworld_edge_get_meta_field_intersect( 'mobile_header_without_grid_padding', $page_id );
		$sticky_container_styles = array();
		$sticky_container_classes = array(
			$class_prefix . ' .edgtf-mobile-header *:not(.edgtf-grid) > .edgtf-vertical-align-containers'
		);

		if ( $mobile_side_padding !== '' ) {
			$sticky_container_styles['padding-left']  = overworld_edge_filter_px( $mobile_side_padding ) . 'px';
			$sticky_container_styles['padding-right'] = overworld_edge_filter_px( $mobile_side_padding ) . 'px';

			$current_style .= overworld_edge_dynamic_css( $sticky_container_classes, $sticky_container_styles );
		}

		$current_style = $current_style . $style;

		return $current_style;
	}

	add_filter( 'overworld_edge_filter_add_page_custom_style', 'overworld_edge_mobile_header_style' );
}