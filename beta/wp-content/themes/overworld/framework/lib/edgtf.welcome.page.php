<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'OverworldEdgeClassWelcomePage' ) ) {
	class OverworldEdgeClassWelcomePage {
		
		/**
		 * Singleton class
		 */
		private static $instance;
		
		/**
		 * Get the instance of OverworldEdgeClassWelcomePage
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		/**
		 * Constructor
		 */
		private function __construct() {
			
			// Theme activation hook
			add_action( 'after_switch_theme', array( $this, 'initActivationHook' ) );
			
			// Welcome page redirect on theme activation
			add_action( 'admin_init', array( $this, 'welcomePageRedirect' ) );
			
			// Add welcome page into theme options
			add_action( 'admin_menu', array( $this, 'addWelcomePage' ), 12 );
			
			//Enqueue theme welcome page scripts
			add_action( 'overworld_edge_action_admin_scripts_init', array( $this, 'enqueueStyles' ) );
		}
		
		/**
		 * Init hooks on theme activation
		 */
		function initActivationHook() {
			
			if ( ! is_network_admin() ) {
				set_transient( '_overworld_edge_welcome_page_redirect', 1, 30 );
			}
		}
		
		/**
		 * Redirect to welcome page on theme activation
		 */
		function welcomePageRedirect() {
			
			// If no activation redirect, bail
			if ( ! get_transient( '_overworld_edge_welcome_page_redirect' ) ) {
				return;
			}
			
			// Delete the redirect transient
			delete_transient( '_overworld_edge_welcome_page_redirect' );
			
			// If activating from network, or bulk, bail
			if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
				return;
			}
			
			// Redirect to welcome page
			wp_safe_redirect( add_query_arg( array( 'page' => 'overworld_edge_welcome_page' ), esc_url( admin_url( 'themes.php' ) ) ) );
			exit;
		}
		
		/**
		 * Add welcome page
		 */
		function addWelcomePage() {
			
			add_theme_page(
				esc_html__( 'About', 'overworld' ),
				esc_html__( 'About', 'overworld' ),
				current_user_can( 'edit_theme_options' ),
				'overworld_edge_welcome_page',
				array( $this, 'welcomePageContent' )
			);
			
			remove_submenu_page( 'themes.php', 'overworld_edge_welcome_page' );
		}
		
		/**
		 * Print welcome page content
		 */
		function welcomePageContent() {
			$edgtf_theme              = wp_get_theme();
			$edgtf_theme_name         = esc_html( $edgtf_theme->get( 'Name' ) );
			$edgtf_theme_description  = esc_html( $edgtf_theme->get( 'Description' ) );
			$edgtf_theme_version      = $edgtf_theme->get( 'Version' );
			$edgtf_theme_screenshot   = file_exists( OVERWORLD_EDGE_ROOT_DIR . '/screenshot.png' ) ? OVERWORLD_EDGE_ROOT . '/screenshot.png' : OVERWORLD_EDGE_ROOT . '/screenshot.jpg';
			$edgtf_welcome_page_class = 'edgtf-welcome-page-' . OVERWORLD_EDGE_PROFILE_SLUG;
			?>
			<div class="wrap about-wrap edgtf-welcome-page <?php echo esc_attr( $edgtf_welcome_page_class ); ?>">
				<div class="edgtf-welcome-page-content">
					<div class="edgtf-welcome-page-logo">
						<img src="<?php echo esc_url( overworld_edge_get_skin_uri() . '/assets/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Profile Logo', 'overworld' ); ?>" />
					</div>
					<h1 class="edgtf-welcome-page-title">
						<?php echo sprintf( esc_html__( 'Welcome to %s', 'overworld' ), $edgtf_theme_name ); ?>
						<small><?php echo esc_html( $edgtf_theme_version ) ?></small>
					</h1>
					<div class="about-text edgtf-welcome-page-text">
						<?php echo sprintf( esc_html__( 'Thank you for installing %s - %s! Everything in %s is streamlined to make your website building experience as simple and fun as possible. We hope you love using it to make a spectacular website.', 'overworld' ),
							$edgtf_theme_name,
							$edgtf_theme_description,
							$edgtf_theme_name
						); ?>
						<img src="<?php echo esc_url( $edgtf_theme_screenshot ); ?>" alt="<?php esc_attr_e( 'Theme Screenshot', 'overworld' ); ?>" />
						
						<h4><?php esc_html_e( 'Useful Links:', 'overworld' ); ?></h4>
						<ul class="edgtf-welcome-page-links">
							<li>
								<a href="<?php echo sprintf('https://%s.ticksy.com/', OVERWORLD_EDGE_PROFILE_SLUG ); ?>" target="_blank"><?php esc_html_e( 'Support Forum', 'overworld' ); ?></a>
							</li>
							<li>
								<a href="<?php echo sprintf('http://overworld.%s-themes.com/documentation/', OVERWORLD_EDGE_PROFILE_SLUG ); ?>" target="_blank"><?php esc_html_e( 'Theme Documentation', 'overworld' ); ?></a>
							</li>
							<li>
								<a href="<?php echo sprintf('https://themeforest.net/user/%s-themes/portfolio/', OVERWORLD_EDGE_PROFILE_SLUG ); ?>" target="_blank"><?php esc_html_e( 'All Our Themes', 'overworld' ); ?></a>
							</li>
							<li>
								<a href="<?php echo add_query_arg( array( 'page' => 'install-required-plugins&plugin_status=install' ), esc_url( admin_url( 'themes.php' ) ) ); ?>"><?php esc_html_e( 'Install Required Plugins', 'overworld' ); ?></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<?php
		}
		
		/**
		 * Enqueue theme welcome page scripts
		 */
		function enqueueStyles() {
			wp_enqueue_style( 'overworld-edge-welcome-page-style', OVERWORLD_EDGE_FRAMEWORK_ADMIN_ASSETS_ROOT . '/css/edgtf-welcome-page.css' );
		}
	}
}

OverworldEdgeClassWelcomePage::getInstance();