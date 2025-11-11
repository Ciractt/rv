<?php

//define constants
define( 'OVERWORLD_EDGE_ROOT', get_template_directory_uri() );
define( 'OVERWORLD_EDGE_ROOT_DIR', get_template_directory() );
define( 'OVERWORLD_EDGE_ASSETS_ROOT', OVERWORLD_EDGE_ROOT . '/assets' );
define( 'OVERWORLD_EDGE_ASSETS_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/assets' );
define( 'OVERWORLD_EDGE_FRAMEWORK_ROOT', OVERWORLD_EDGE_ROOT . '/framework' );
define( 'OVERWORLD_EDGE_FRAMEWORK_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/framework' );
define( 'OVERWORLD_EDGE_FRAMEWORK_ADMIN_ASSETS_ROOT', OVERWORLD_EDGE_ROOT . '/framework/admin/assets' );
define( 'OVERWORLD_EDGE_FRAMEWORK_ICONS_ROOT', OVERWORLD_EDGE_ROOT . '/framework/lib/icons-pack' );
define( 'OVERWORLD_EDGE_FRAMEWORK_ICONS_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/framework/lib/icons-pack' );
define( 'OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT', OVERWORLD_EDGE_ROOT . '/framework/modules' );
define( 'OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/framework/modules' );
define( 'OVERWORLD_EDGE_FRAMEWORK_HEADER_ROOT', OVERWORLD_EDGE_ROOT . '/framework/modules/header' );
define( 'OVERWORLD_EDGE_FRAMEWORK_HEADER_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/framework/modules/header' );
define( 'OVERWORLD_EDGE_FRAMEWORK_HEADER_TYPES_ROOT', OVERWORLD_EDGE_ROOT . '/framework/modules/header/types' );
define( 'OVERWORLD_EDGE_FRAMEWORK_HEADER_TYPES_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/framework/modules/header/types' );
define( 'OVERWORLD_EDGE_FRAMEWORK_SEARCH_ROOT', OVERWORLD_EDGE_ROOT . '/framework/modules/search' );
define( 'OVERWORLD_EDGE_FRAMEWORK_SEARCH_ROOT_DIR', OVERWORLD_EDGE_ROOT_DIR . '/framework/modules/search' );
define( 'OVERWORLD_EDGE_THEME_ENV', 'false' );
define( 'OVERWORLD_EDGE_PROFILE_SLUG', 'edge' );
define( 'OVERWORLD_EDGE_OPTIONS_SLUG', 'overworld_edge_theme_menu');

//include necessary files
include_once OVERWORLD_EDGE_ROOT_DIR . '/framework/edgtf-framework.php';
include_once OVERWORLD_EDGE_ROOT_DIR . '/includes/nav-menu/edgtf-menu.php';
require_once OVERWORLD_EDGE_ROOT_DIR . '/includes/plugins/class-tgm-plugin-activation.php';
include_once OVERWORLD_EDGE_ROOT_DIR . '/includes/plugins/plugins-activation.php';
include_once OVERWORLD_EDGE_ROOT_DIR . '/assets/custom-styles/general-custom-styles.php';
include_once OVERWORLD_EDGE_ROOT_DIR . '/assets/custom-styles/general-custom-styles-responsive.php';

if ( file_exists( OVERWORLD_EDGE_ROOT_DIR . '/export' ) ) {
	include_once OVERWORLD_EDGE_ROOT_DIR . '/export/export.php';
}

if ( ! is_admin() ) {
	include_once OVERWORLD_EDGE_ROOT_DIR . '/includes/edgtf-body-class-functions.php';
	include_once OVERWORLD_EDGE_ROOT_DIR . '/includes/edgtf-loading-spinners.php';
}