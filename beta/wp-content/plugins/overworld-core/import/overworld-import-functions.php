<?php

if ( ! function_exists( 'overworld_core_import_object' ) ) {
	function overworld_core_import_object() {
		$overworld_core_import_object = new OverworldCoreImport();
	}
	
	add_action( 'init', 'overworld_core_import_object' );
}

if ( ! function_exists( 'overworld_core_data_import' ) ) {
	function overworld_core_data_import() {
		$importObject = OverworldCoreImport::getInstance();
		
		if ( $_POST['import_attachments'] == 1 ) {
			$importObject->attachments = true;
		} else {
			$importObject->attachments = false;
		}
		
		$folder = "overworld/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$importObject->import_content( $folder . $_POST['xml'] );
		
		die();
	}
	
	add_action( 'wp_ajax_overworld_core_action_import_content', 'overworld_core_data_import' );
}

if ( ! function_exists( 'overworld_core_widgets_import' ) ) {
	function overworld_core_widgets_import() {
		$importObject = OverworldCoreImport::getInstance();
		
		$folder = "overworld/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$importObject->import_widgets( $folder . 'widgets.txt', $folder . 'custom_sidebars.txt' );
		
		die();
	}
	
	add_action( 'wp_ajax_overworld_core_action_import_widgets', 'overworld_core_widgets_import' );
}

if ( ! function_exists( 'overworld_core_options_import' ) ) {
	function overworld_core_options_import() {
		$importObject = OverworldCoreImport::getInstance();
		
		$folder = "overworld/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$importObject->import_options( $folder . 'options.txt' );
		
		die();
	}
	
	add_action( 'wp_ajax_overworld_core_action_import_options', 'overworld_core_options_import' );
}

if ( ! function_exists( 'overworld_core_other_import' ) ) {
	function overworld_core_other_import() {
		$importObject = OverworldCoreImport::getInstance();
		
		$folder = "overworld/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$importObject->import_options( $folder . 'options.txt' );
		$importObject->import_widgets( $folder . 'widgets.txt', $folder . 'custom_sidebars.txt' );
		$importObject->import_menus( $folder . 'menus.txt' );
		$importObject->import_settings_pages( $folder . 'settingpages.txt' );
		
		$importObject->edgtf_update_meta_fields_after_import( $folder );
		$importObject->edgtf_update_options_after_import( $folder );
		
		if ( overworld_core_is_revolution_slider_installed() ) {
			$importObject->rev_slider_import( $folder );
		}
		
		die();
	}
	
	add_action( 'wp_ajax_overworld_core_action_import_other_elements', 'overworld_core_other_import' );
}