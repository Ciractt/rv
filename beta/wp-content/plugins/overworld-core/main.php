<?php
/*
Plugin Name: Overworld Core
Description: Plugin that adds all post types needed by our theme
Author: Edge Themes
Version: 1.2
*/

require_once 'load.php';

add_action( 'after_setup_theme', array( OverworldCore\CPT\PostTypesRegister::getInstance(), 'register' ) );

if ( ! function_exists( 'overworld_core_activation' ) ) {
	/**
	 * Triggers when plugin is activated. It calls flush_rewrite_rules
	 * and defines overworld_edge_action_core_on_activate action
	 */
	function overworld_core_activation() {
		do_action( 'overworld_edge_action_core_on_activate' );
		
		OverworldCore\CPT\PostTypesRegister::getInstance()->register();
		flush_rewrite_rules();
	}
	
	register_activation_hook( __FILE__, 'overworld_core_activation' );
}

if ( ! function_exists( 'overworld_core_text_domain' ) ) {
	/**
	 * Loads plugin text domain so it can be used in translation
	 */
	function overworld_core_text_domain() {
		load_plugin_textdomain( 'overworld-core', false, OVERWORLD_CORE_REL_PATH . '/languages' );
	}
	
	add_action( 'plugins_loaded', 'overworld_core_text_domain' );
}

if ( ! function_exists( 'overworld_core_version_class' ) ) {
	/**
	 * Adds plugins version class to body
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	function overworld_core_version_class( $classes ) {
		$classes[] = 'overworld-core-' . OVERWORLD_CORE_VERSION;
		
		return $classes;
	}
	
	add_filter( 'body_class', 'overworld_core_version_class' );
}

if ( ! function_exists( 'overworld_core_theme_installed' ) ) {
	/**
	 * Checks whether theme is installed or not
	 * @return bool
	 */
	function overworld_core_theme_installed() {
		return defined( 'OVERWORLD_EDGE_ROOT' );
	}
}

if ( ! function_exists( 'overworld_core_visual_composer_installed' ) ) {
	/**
	 * Function that checks if Visual Composer plugin installed
	 *
	 * @return bool
	 */
	function overworld_core_visual_composer_installed() {
		return class_exists( 'WPBakeryVisualComposerAbstract' );
	}
}

if ( ! function_exists( 'overworld_core_is_woocommerce_installed' ) ) {
	/**
	 * Function that checks if woocommerce is installed
	 *
	 * @return bool
	 */
	function overworld_core_is_woocommerce_installed() {
		return function_exists( 'is_woocommerce' );
	}
}

if ( ! function_exists( 'overworld_core_is_woocommerce_integration_installed' ) ) {
	//is Edge Woocommerce Integration installed?
	function overworld_core_is_woocommerce_integration_installed() {
		return defined( 'OVERWORLD_CHECKOUT_INTEGRATION' );
	}
}

if ( ! function_exists( 'overworld_core_is_revolution_slider_installed' ) ) {
	function overworld_core_is_revolution_slider_installed() {
		return class_exists( 'RevSliderFront' );
	}
}

if ( ! function_exists( 'overworld_core_is_wpml_installed' ) ) {
	/**
	 * Function that checks if WPML plugin is installed
	 * @return bool
	 *
	 * @version 0.1
	 */
	function overworld_core_is_wpml_installed() {
		return defined( 'ICL_SITEPRESS_VERSION' );
	}
}

if ( ! function_exists( 'overworld_core_theme_menu' ) ) {
	/**
	 * Function that generates admin menu for options page.
	 * It generates one admin page per options page.
	 */
	function overworld_core_theme_menu() {
		if ( overworld_core_theme_installed() ) {
			
			global $overworld_edge_global_Framework;
			overworld_edge_init_theme_options();
			
			$page_hook_suffix = add_menu_page(
				esc_html__( 'Overworld Options', 'overworld-core' ),                                             // The value used to populate the browser's title bar when the menu page is active
				esc_html__( 'Overworld Options', 'overworld-core' ),                                             // The text of the menu in the administrator's sidebar
				'administrator',                                                                               // What roles are able to access the menu
				OVERWORLD_EDGE_OPTIONS_SLUG,                                                                             // The ID used to bind submenu items to this menu
				array( $overworld_edge_global_Framework->getSkin(), 'renderOptions' ),                         // The callback function used to render this menu
				$overworld_edge_global_Framework->getSkin()->getSkinURI() . '/assets/img/admin-logo-icon.png', // Icon For menu Item
				100                                                                                            // Position
			);
			
			foreach ( $overworld_edge_global_Framework->edgtOptions->adminPages as $key => $value ) {
				$slug = ! empty( $value->slug ) ? '_tab' . $value->slug : '';
				
				$subpage_hook_suffix = add_submenu_page(
					OVERWORLD_EDGE_OPTIONS_SLUG,
					esc_html__( 'Overworld Options - ', 'overworld-core' ) . $value->title, // The value used to populate the browser's title bar when the menu page is active
					$value->title,                                                        // The text of the menu in the administrator's sidebar
					'administrator',                                                      // What roles are able to access the menu
					OVERWORLD_EDGE_OPTIONS_SLUG . $slug,                                            // The ID used to bind submenu items to this menu
					array( $overworld_edge_global_Framework->getSkin(), 'renderOptions' )
				);
				
				add_action( 'admin_print_scripts-' . $subpage_hook_suffix, 'overworld_edge_enqueue_admin_scripts' );
				add_action( 'admin_print_styles-' . $subpage_hook_suffix, 'overworld_edge_enqueue_admin_styles' );
			};
			
			add_action( 'admin_print_scripts-' . $page_hook_suffix, 'overworld_edge_enqueue_admin_scripts' );
			add_action( 'admin_print_styles-' . $page_hook_suffix, 'overworld_edge_enqueue_admin_styles' );
		}
	}
	
	add_action( 'admin_menu', 'overworld_core_theme_menu' );
}

if ( ! function_exists( 'overworld_core_theme_menu_backup_options' ) ) {
	/**
	 * Function that generates admin menu for options page.
	 * It generates one admin page per options page.
	 */
	function overworld_core_theme_menu_backup_options() {
		if ( overworld_core_theme_installed() ) {
			global $overworld_edge_global_Framework;
			
			$slug             = "_backup_options";
			$page_hook_suffix = add_submenu_page(
				OVERWORLD_EDGE_OPTIONS_SLUG,
				esc_html__( 'Overworld Options - Backup Options', 'overworld-core' ), // The value used to populate the browser's title bar when the menu page is active
				esc_html__( 'Backup Options', 'overworld-core' ),                // The text of the menu in the administrator's sidebar
				'administrator',                                             // What roles are able to access the menu
				OVERWORLD_EDGE_OPTIONS_SLUG . $slug,                     // The ID used to bind submenu items to this menu
				array( $overworld_edge_global_Framework->getSkin(), 'renderBackupOptions' )
			);
			
			add_action( 'admin_print_scripts-' . $page_hook_suffix, 'overworld_edge_enqueue_admin_scripts' );
			add_action( 'admin_print_styles-' . $page_hook_suffix, 'overworld_edge_enqueue_admin_styles' );
		}
	}
	
	add_action( 'admin_menu', 'overworld_core_theme_menu_backup_options' );
}

if ( ! function_exists( 'overworld_core_theme_admin_bar_menu_options' ) ) {
	/**
	 * Add a link to the WP Toolbar
	 */
	function overworld_core_theme_admin_bar_menu_options( $wp_admin_bar ) {
		if ( overworld_core_theme_installed() && current_user_can( 'administrator' ) ) {
			global $overworld_edge_global_Framework;
			
			$args = array(
				'id'    => 'overworld-admin-bar-options',
				'title' => sprintf( '<span class="ab-icon dashicons-before dashicons-admin-generic"></span> %s', esc_html__( 'Overworld Options', 'overworld-core' ) ),
				'href'  => esc_url( admin_url( 'admin.php?page=' . OVERWORLD_EDGE_OPTIONS_SLUG ) )
			);
			
			$wp_admin_bar->add_node( $args );
			
			foreach ( $overworld_edge_global_Framework->edgtOptions->adminPages as $key => $value ) {
				$suffix = ! empty( $value->slug ) ? '_tab' . $value->slug : '';
				
				$args = array(
					'id'     => 'overworld-admin-bar-options-' . $suffix,
					'title'  => $value->title,
					'parent' => 'overworld-admin-bar-options',
					'href'   => esc_url( admin_url( 'admin.php?page=' . OVERWORLD_EDGE_OPTIONS_SLUG . $suffix ) )
				);
				
				$wp_admin_bar->add_node( $args );
			};
		}
	}
	
	add_action( 'admin_bar_menu', 'overworld_core_theme_admin_bar_menu_options', 999 );
}

if ( ! function_exists( 'overworld_core_enqueue_our_prettyphoto_scripts_for_theme' ) ) {
	/**
	 * Function that includes our prettyphoto script
	 */
	function overworld_core_enqueue_our_prettyphoto_scripts_for_theme() {
		
		if ( overworld_core_theme_installed() && overworld_core_visual_composer_installed() ) {
			wp_deregister_script( 'prettyphoto' );
			wp_enqueue_script( 'prettyphoto', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.prettyPhoto.js', array( 'jquery' ), false, true );
		}
	}
	
	add_action( 'overworld_edge_action_enqueue_third_party_scripts', 'overworld_core_enqueue_our_prettyphoto_scripts_for_theme' );
}