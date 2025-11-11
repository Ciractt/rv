<?php
/*
Plugin Name: Overworld Twitter Feed
Description: Plugin that adds Twitter feed functionality to our theme
Author: Edge Themes
Version: 1.0.1
*/

define( 'OVERWORLD_TWITTER_FEED_VERSION', '1.0.1' );
define( 'OVERWORLD_TWITTER_ABS_PATH', dirname( __FILE__ ) );
define( 'OVERWORLD_TWITTER_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'OVERWORLD_TWITTER_URL_PATH', plugin_dir_url( __FILE__ ) );
define( 'OVERWORLD_TWITTER_ASSETS_PATH', OVERWORLD_TWITTER_ABS_PATH . '/assets' );
define( 'OVERWORLD_TWITTER_ASSETS_URL_PATH', OVERWORLD_TWITTER_URL_PATH . 'assets' );
define( 'OVERWORLD_TWITTER_SHORTCODES_PATH', OVERWORLD_TWITTER_ABS_PATH . '/shortcodes' );
define( 'OVERWORLD_TWITTER_SHORTCODES_URL_PATH', OVERWORLD_TWITTER_URL_PATH . 'shortcodes' );

include_once 'load.php';

if ( ! function_exists( 'overworld_twitter_theme_installed' ) ) {
	/**
	 * Checks whether theme is installed or not
	 * @return bool
	 */
	function overworld_twitter_theme_installed() {
		return defined( 'OVERWORLD_EDGE_ROOT' );
	}
}

if ( ! function_exists( 'overworld_twitter_feed_text_domain' ) ) {
	/**
	 * Loads plugin text domain so it can be used in translation
	 */
	function overworld_twitter_feed_text_domain() {
		load_plugin_textdomain( 'overworld-twitter-feed', false, OVERWORLD_TWITTER_REL_PATH . '/languages' );
	}
	
	add_action( 'plugins_loaded', 'overworld_twitter_feed_text_domain' );
}