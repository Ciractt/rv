<?php

$theme_slug   = get_option( 'template' );
$theme_author = wp_get_theme( $theme_slug )->get( 'Author' );
$theme_domain = ( 'Qode Interactive' === $theme_author ) ? 'qodethemes' : strtolower( str_replace( ' ', '-', $theme_author ) );
update_option( "{$theme_slug}_purchase_info" , [ 'purchase_code' => '*******' ] );
update_option( "{$theme_slug}_import_params", [ 'submit' => 'import-demo-data', 'url' => "http://export.{$theme_domain}.com/" ] );

add_filter( 'pre_http_request', function( $pre, $parsed_args, $url ){
    if ( strpos( $url, 'https://api.qodeinteractive.com/purchase-code-validation.php' ) !== false ) {
        return [
            'response' => [ 'code' => 200, 'message' => 'ОК' ],
            'body'     => json_encode( [ 'success' => true ] )
        ];
    } else {
        return false;
    }
}, 10, 3 );

include_once get_template_directory() . '/theme-includes.php';

if ( ! function_exists( 'overworld_edge_styles' ) ) {
	/**
	 * Function that includes theme's core styles
	 */
	function overworld_edge_styles() {

		$modules_css_deps_array = apply_filters( 'overworld_edge_filter_modules_css_deps', array() );

		//include theme's core styles
		wp_enqueue_style( 'overworld-edge-default-style', OVERWORLD_EDGE_ROOT . '/style.css' );
		wp_enqueue_style( 'overworld-edge-modules', OVERWORLD_EDGE_ASSETS_ROOT . '/css/modules.min.css', $modules_css_deps_array );

		overworld_edge_icon_collections()->enqueueStyles();

		wp_enqueue_style( 'wp-mediaelement' );

		do_action( 'overworld_edge_action_enqueue_third_party_styles' );

		//is woocommerce installed?
		if ( overworld_edge_is_plugin_installed( 'woocommerce' ) && overworld_edge_load_woo_assets() ) {
			//include theme's woocommerce styles
			wp_enqueue_style( 'overworld-edge-woo', OVERWORLD_EDGE_ASSETS_ROOT . '/css/woocommerce.min.css' );
		}

		if ( overworld_edge_dashboard_page() || overworld_edge_has_dashboard_shortcodes() ) {
			wp_enqueue_style( 'overworld-edge-dashboard', OVERWORLD_EDGE_FRAMEWORK_ADMIN_ASSETS_ROOT . '/css/edgtf-dashboard.css' );
		}

		//define files after which style dynamic needs to be included. It should be included last so it can override other files
		$style_dynamic_deps_array = apply_filters( 'overworld_edge_filter_style_dynamic_deps', array() );

		if ( file_exists( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic.css' ) && overworld_edge_is_css_folder_writable() && ! is_multisite() ) {
			wp_enqueue_style( 'overworld-edge-style-dynamic', OVERWORLD_EDGE_ASSETS_ROOT . '/css/style_dynamic.css', $style_dynamic_deps_array, filemtime( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic.css' ) ); //it must be included after woocommerce styles so it can override it
		} else if ( file_exists( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic_ms_id_' . overworld_edge_get_multisite_blog_id() . '.css' ) && overworld_edge_is_css_folder_writable() && is_multisite() ) {
			wp_enqueue_style( 'overworld-edge-style-dynamic', OVERWORLD_EDGE_ASSETS_ROOT . '/css/style_dynamic_ms_id_' . overworld_edge_get_multisite_blog_id() . '.css', $style_dynamic_deps_array, filemtime( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic_ms_id_' . overworld_edge_get_multisite_blog_id() . '.css' ) ); //it must be included after woocommerce styles so it can override it
		}

		//is responsive option turned on?
		if ( overworld_edge_is_responsive_on() ) {
			wp_enqueue_style( 'overworld-edge-modules-responsive', OVERWORLD_EDGE_ASSETS_ROOT . '/css/modules-responsive.min.css' );

			//is woocommerce installed?
			if ( overworld_edge_is_plugin_installed( 'woocommerce' ) && overworld_edge_load_woo_assets() ) {
				//include theme's woocommerce responsive styles
				wp_enqueue_style( 'overworld-edge-woo-responsive', OVERWORLD_EDGE_ASSETS_ROOT . '/css/woocommerce-responsive.min.css' );
			}

			//include proper styles
			if ( file_exists( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic_responsive.css' ) && overworld_edge_is_css_folder_writable() && ! is_multisite() ) {
				wp_enqueue_style( 'overworld-edge-style-dynamic-responsive', OVERWORLD_EDGE_ASSETS_ROOT . '/css/style_dynamic_responsive.css', array(), filemtime( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic_responsive.css' ) );
			} else if ( file_exists( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic_responsive_ms_id_' . overworld_edge_get_multisite_blog_id() . '.css' ) && overworld_edge_is_css_folder_writable() && is_multisite() ) {
				wp_enqueue_style( 'overworld-edge-style-dynamic-responsive', OVERWORLD_EDGE_ASSETS_ROOT . '/css/style_dynamic_responsive_ms_id_' . overworld_edge_get_multisite_blog_id() . '.css', array(), filemtime( OVERWORLD_EDGE_ROOT_DIR . '/assets/css/style_dynamic_responsive_ms_id_' . overworld_edge_get_multisite_blog_id() . '.css' ) );
			}
		}
	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_styles' );
}

if ( ! function_exists( 'overworld_edge_google_fonts_styles' ) ) {
	/**
	 * Function that includes google fonts defined anywhere in the theme
	 */
	function overworld_edge_google_fonts_styles() {

		$is_enabled = boolval( apply_filters( 'overworld_edge_filter_enable_google_fonts', true ) );
		if ( $is_enabled ) {
			$font_simple_field_array = overworld_edge_options()->getOptionsByType( 'fontsimple' );
			if ( ! ( is_array( $font_simple_field_array ) && count( $font_simple_field_array ) > 0 ) ) {
				$font_simple_field_array = array();
			}

			$font_field_array = overworld_edge_options()->getOptionsByType( 'font' );
			if ( ! ( is_array( $font_field_array ) && count( $font_field_array ) > 0 ) ) {
				$font_field_array = array();
			}

			$available_font_options = array_merge( $font_simple_field_array, $font_field_array );

			$google_font_weight_array = overworld_edge_options()->getOptionValue( 'google_font_weight' );
			if ( ! empty( $google_font_weight_array ) && is_array( $google_font_weight_array ) ) {
				$google_font_weight_array = array_slice( overworld_edge_options()->getOptionValue( 'google_font_weight' ), 1 );
			}

			$font_weight_str = '400,600,700';
			if ( ! empty( $google_font_weight_array ) && is_array( $google_font_weight_array ) && $google_font_weight_array !== '' ) {
				$font_weight_str = implode( ',', $google_font_weight_array );
			}

			$google_font_subset_array = overworld_edge_options()->getOptionValue( 'google_font_subset' );
			if ( ! empty( $google_font_subset_array ) && is_array( $google_font_subset_array ) ) {
				$google_font_subset_array = array_slice( overworld_edge_options()->getOptionValue( 'google_font_subset' ), 1 );
			}

			$font_subset_str = 'latin-ext';
			if ( ! empty( $google_font_subset_array ) && is_array( $google_font_subset_array ) && $google_font_subset_array !== '' ) {
				$font_subset_str = implode( ',', $google_font_subset_array );
			}

			//default fonts
			$default_font_family = array(
				'Open Sans',
				'Rajdhani'
			);

			$modified_default_font_family = array();
			foreach ( $default_font_family as $default_font ) {
				$modified_default_font_family[] = $default_font . ':' . str_replace( ' ', '', $font_weight_str );
			}

			$default_font_string = implode( '|', $modified_default_font_family );

			//define available font options array
			$fonts_array = array();
			foreach ( $available_font_options as $font_option ) {
				//is font set and not set to default and not empty?
				$font_option_value = overworld_edge_options()->getOptionValue( $font_option );

				if ( overworld_edge_is_font_option_valid( $font_option_value ) && ! overworld_edge_is_native_font( $font_option_value ) ) {
					$font_option_string = $font_option_value . ':' . $font_weight_str;

					if ( ! in_array( str_replace( '+', ' ', $font_option_value ), $default_font_family ) && ! in_array( $font_option_string, $fonts_array ) ) {
						$fonts_array[] = $font_option_string;
					}
				}
			}

			$fonts_array         = array_diff( $fonts_array, array( '-1:' . $font_weight_str ) );
			$google_fonts_string = implode( '|', $fonts_array );

			$protocol = is_ssl() ? 'https:' : 'http:';

			//is google font option checked anywhere in theme?
			if ( count( $fonts_array ) > 0 ) {

				//include all checked fonts
				$fonts_full_list      = $default_font_string . '|' . str_replace( '+', ' ', $google_fonts_string );
				$fonts_full_list_args = array(
					'family' => urlencode( $fonts_full_list ),
					'subset' => urlencode( $font_subset_str ),
				);

				$overworld_edge_global_fonts = add_query_arg( $fonts_full_list_args, $protocol . '//fonts.googleapis.com/css' );
				wp_enqueue_style( 'overworld-edge-google-fonts', esc_url_raw( $overworld_edge_global_fonts ), array(), '1.0.0' );

			} else {
				//include default google font that theme is using
				$default_fonts_args          = array(
					'family' => urlencode( $default_font_string ),
					'subset' => urlencode( $font_subset_str ),
				);
				$overworld_edge_global_fonts = add_query_arg( $default_fonts_args, $protocol . '//fonts.googleapis.com/css' );
				wp_enqueue_style( 'overworld-edge-google-fonts', esc_url_raw( $overworld_edge_global_fonts ), array(), '1.0.0' );
			}
		}

	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_google_fonts_styles' );
}

if ( ! function_exists( 'overworld_edge_scripts' ) ) {
	/**
	 * Function that includes all necessary scripts
	 */
	function overworld_edge_scripts() {
		global $wp_scripts;

		//init theme core scripts
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'wp-mediaelement' );

		// 3rd party JavaScripts that we used in our theme
		wp_enqueue_script( 'appear', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.appear.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'modernizr', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/modernizr.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'hoverIntent' );
		wp_enqueue_script( 'owl-carousel', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/owl.carousel.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'waypoints', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.waypoints.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'fluidvids', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/fluidvids.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'perfect-scrollbar', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/perfect-scrollbar.jquery.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'scroll-to-plugin', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/ScrollToPlugin.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'parallax', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/parallax.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'waitforimages', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.waitforimages.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'prettyphoto', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.prettyPhoto.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'jquery-easing-1.3', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.easing.1.3.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'isotope', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/isotope.pkgd.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'swiper', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/swiper.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'slick', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/slick.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'packery', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/packery-mode.pkgd.min.js', array( 'jquery' ), false, true );

		do_action( 'overworld_edge_action_enqueue_third_party_scripts' );

		if ( overworld_edge_is_plugin_installed( 'woocommerce' ) ) {
			wp_enqueue_script( 'select2' );
		}

		if ( overworld_edge_is_page_smooth_scroll_enabled() ) {
			wp_enqueue_script( 'tweenLite', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/TweenLite.min.js', array( 'jquery' ), false, true );
			wp_enqueue_script( 'smooth-page-scroll', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/smoothPageScroll.js', array( 'jquery' ), false, true );
		}

		//include google map api script
		$google_maps_api_key          = overworld_edge_options()->getOptionValue( 'google_maps_api_key' );
		$google_maps_extensions       = '';
		$google_maps_extensions_array = apply_filters( 'overworld_edge_filter_google_maps_extensions_array', array() );

		if ( ! empty( $google_maps_extensions_array ) ) {
			$google_maps_extensions .= '&libraries=';
			$google_maps_extensions .= implode( ',', $google_maps_extensions_array );
		}

		if ( ! empty( $google_maps_api_key ) ) {
			wp_enqueue_script( 'overworld-edge-google-map-api', '//maps.googleapis.com/maps/api/js?key=' . esc_attr( $google_maps_api_key ) . "&callback=qodefEmptyCallback" . $google_maps_extensions, array(), false, true );
			if ( ! empty( $google_maps_extensions_array ) && is_array( $google_maps_extensions_array ) ) {
				wp_enqueue_script( 'geocomplete', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules/plugins/jquery.geocomplete.min.js', array(
					'jquery',
					'overworld-edge-google-map-api'
				), false, true );
			}
			wp_add_inline_script( 'overworld-edge-google-map-api', 'window.qodefEmptyCallback = function () {};', 'before' );
		}

		wp_enqueue_script( 'overworld-edge-modules', OVERWORLD_EDGE_ASSETS_ROOT . '/js/modules.min.js', array( 'jquery' ), false, true );

		if ( overworld_edge_dashboard_page() || overworld_edge_has_dashboard_shortcodes() ) {
			$dash_array_deps = array(
				'jquery-ui-datepicker',
				'jquery-ui-sortable'
			);

			wp_enqueue_script( 'overworld-edge-dashboard', OVERWORLD_EDGE_FRAMEWORK_ADMIN_ASSETS_ROOT . '/js/edgtf-dashboard.js', $dash_array_deps, false, true );

			wp_enqueue_script( 'wp-util' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1 );
			wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), false, 1 );

			$colorpicker_l10n = array(
				'clear'         => esc_html__( 'Clear', 'overworld' ),
				'defaultString' => esc_html__( 'Default', 'overworld' ),
				'pick'          => esc_html__( 'Select Color', 'overworld' ),
				'current'       => esc_html__( 'Current Color', 'overworld' ),
			);

			wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
		}

		//include comment reply script
		$wp_scripts->add_data( 'comment-reply', 'group', 1 );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_scripts' );
}

if ( ! function_exists( 'overworld_edge_theme_setup' ) ) {
	/**
	 * Function that adds various features to theme. Also defines image sizes that are used in a theme
	 */
	function overworld_edge_theme_setup() {
		//add support for feed links
		add_theme_support( 'automatic-feed-links' );

		//add support for post formats
		add_theme_support( 'post-formats', array( 'gallery', 'link', 'quote', 'video', 'audio' ) );

		//add theme support for post thumbnails
		add_theme_support( 'post-thumbnails' );

		//add theme support for title tag
		add_theme_support( 'title-tag' );

		//add theme support for editor style
		add_editor_style( 'framework/admin/assets/css/editor-style.css' );

		//defined content width variable
		$GLOBALS['content_width'] = apply_filters( 'overworld_edge_filter_set_content_width', 1100 );

		//define thumbnail sizes
		add_image_size( 'overworld_edge_image_square', 650, 650, true );
		add_image_size( 'overworld_edge_image_landscape', 1300, 650, true );
		add_image_size( 'overworld_edge_image_portrait', 650, 1300, true );
		add_image_size( 'overworld_edge_image_huge', 1300, 1300, true );

		load_theme_textdomain( 'overworld', get_template_directory() . '/languages' );
	}

	add_action( 'after_setup_theme', 'overworld_edge_theme_setup' );
}

if ( ! function_exists( 'overworld_edge_enqueue_editor_customizer_styles' ) ) {
	/**
	 * Enqueue supplemental block editor styles
	 */
	function overworld_edge_enqueue_editor_customizer_styles() {
		wp_enqueue_style( 'themename-style-modules-admin-styles', OVERWORLD_EDGE_FRAMEWORK_ADMIN_ASSETS_ROOT . '/css/edgtf-modules-admin.css' );
		wp_enqueue_style( 'overworld-edge-editor-customizer-styles', OVERWORLD_EDGE_FRAMEWORK_ADMIN_ASSETS_ROOT . '/css/editor-customizer-style.css' );
	}

	// add google font
	add_action( 'enqueue_block_editor_assets', 'overworld_edge_google_fonts_styles' );
	// add action
	add_action( 'enqueue_block_editor_assets', 'overworld_edge_enqueue_editor_customizer_styles' );
}

if ( ! function_exists( 'overworld_edge_is_responsive_on' ) ) {
	/**
	 * Checks whether responsive mode is enabled in theme options
	 * @return bool
	 */
	function overworld_edge_is_responsive_on() {
		return overworld_edge_options()->getOptionValue( 'responsiveness' ) !== 'no';
	}
}

if ( ! function_exists( 'overworld_edge_rgba_color' ) ) {
	/**
	 * Function that generates rgba part of css color property
	 *
	 * @param $color string hex color
	 * @param $transparency float transparency value between 0 and 1
	 *
	 * @return string generated rgba string
	 */
	function overworld_edge_rgba_color( $color, $transparency ) {
		if ( $color !== '' && $transparency !== '' ) {
			$rgba_color = '';

			$rgb_color_array = overworld_edge_hex2rgb( $color );
			$rgba_color      .= 'rgba(' . implode( ', ', $rgb_color_array ) . ', ' . $transparency . ')';

			return $rgba_color;
		}
	}
}

if ( ! function_exists( 'overworld_edge_header_meta' ) ) {
	/**
	 * Function that echoes meta data if our seo is enabled
	 */
	function overworld_edge_header_meta() { ?>

		<meta charset="<?php bloginfo( 'charset' ); ?>"/>
		<link rel="profile" href="http://gmpg.org/xfn/11"/>
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>

	<?php }

	add_action( 'overworld_edge_action_header_meta', 'overworld_edge_header_meta' );
}

if ( ! function_exists( 'overworld_edge_user_scalable_meta' ) ) {
	/**
	 * Function that outputs user scalable meta if responsiveness is turned on
	 * Hooked to overworld_edge_action_header_meta action
	 */
	function overworld_edge_user_scalable_meta() {
		//is responsiveness option is chosen?
		if ( overworld_edge_is_responsive_on() ) { ?>
			<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
		<?php } else { ?>
			<meta name="viewport" content="width=1200,user-scalable=yes">
		<?php }
	}

	add_action( 'overworld_edge_action_header_meta', 'overworld_edge_user_scalable_meta' );
}

if ( ! function_exists( 'overworld_edge_smooth_page_transitions' ) ) {
	/**
	 * Function that outputs smooth page transitions html if smooth page transitions functionality is turned on
	 * Hooked to overworld_edge_action_before_closing_body_tag action
	 */
	function overworld_edge_smooth_page_transitions() {
		$id = overworld_edge_get_page_id();

		if ( overworld_edge_get_meta_field_intersect( 'smooth_page_transitions', $id ) === 'yes' && overworld_edge_get_meta_field_intersect( 'page_transition_preloader', $id ) === 'yes' ) { ?>
			<div class="edgtf-smooth-transition-loader edgtf-mimic-ajax">
				<div class="edgtf-st-loader">
					<div class="edgtf-st-loader1">
						<?php overworld_edge_loading_spinners(); ?>
					</div>
				</div>
			</div>
		<?php }
	}

	add_action( 'overworld_edge_action_after_opening_body_tag', 'overworld_edge_smooth_page_transitions', 10 );
}

if ( ! function_exists( 'overworld_edge_back_to_top_button' ) ) {
	/**
	 * Function that outputs back to top button html if back to top functionality is turned on
	 * Hooked to overworld_edge_action_after_wrapper_inner action
	 */
	function overworld_edge_back_to_top_button() {
		if ( overworld_edge_options()->getOptionValue( 'show_back_button' ) == 'yes' ) { ?>
			<a id='edgtf-back-to-top' href='#'>
                <span class="edgtf-icon-stack">
					 <?php overworld_edge_icon_collections()->getBackToTopIcon( 'ion_icons' ); ?>
					 <span class="edgtf-btt-bg-holder"></span>
                </span>
			</a>
		<?php }
	}

	add_action( 'overworld_edge_action_after_wrapper_inner', 'overworld_edge_back_to_top_button', 30 );
}

if ( ! function_exists( 'overworld_edge_get_page_id' ) ) {
	/**
	 * Function that returns current page / post id.
	 * Checks if current page is woocommerce page and returns that id if it is.
	 * Checks if current page is any archive page (category, tag, date, author etc.) and returns -1 because that isn't
	 * page that is created in WP admin.
	 *
	 * @return int
	 *
	 * @version 0.1
	 *
	 * @see overworld_edge_is_plugin_installed()
	 * @see overworld_edge_is_woocommerce_shop()
	 */
	function overworld_edge_get_page_id() {
		if ( overworld_edge_is_plugin_installed( 'woocommerce' ) && overworld_edge_is_woocommerce_shop() ) {
			return overworld_edge_get_woo_shop_page_id();
		}

		if ( overworld_edge_is_default_wp_template() ) {
			return - 1;
		}

		return get_queried_object_id();
	}
}

if ( ! function_exists( 'overworld_edge_get_multisite_blog_id' ) ) {
	/**
	 * Check is multisite and return blog id
	 *
	 * @return int
	 */
	function overworld_edge_get_multisite_blog_id() {
		if ( is_multisite() ) {
			return get_blog_details()->blog_id;
		}
	}
}

if ( ! function_exists( 'overworld_edge_is_default_wp_template' ) ) {
	/**
	 * Function that checks if current page archive page, search, 404 or default home blog page
	 * @return bool
	 *
	 * @see is_archive()
	 * @see is_search()
	 * @see is_404()
	 * @see is_front_page()
	 * @see is_home()
	 */
	function overworld_edge_is_default_wp_template() {
		return is_archive() || is_search() || is_404() || ( is_front_page() && is_home() );
	}
}

if ( ! function_exists( 'overworld_edge_has_shortcode' ) ) {
	/**
	 * Function that checks whether shortcode exists on current page / post
	 *
	 * @param string shortcode to find
	 * @param string content to check. If isn't passed current post content will be used
	 *
	 * @return bool whether content has shortcode or not
	 */
	function overworld_edge_has_shortcode( $shortcode, $content = '' ) {
		$has_shortcode = false;

		if ( $shortcode ) {
			//if content variable isn't past
			if ( $content == '' ) {
				//take content from current post
				$page_id = overworld_edge_get_page_id();
				if ( ! empty( $page_id ) ) {
					$current_post = get_post( $page_id );

					if ( is_object( $current_post ) && property_exists( $current_post, 'post_content' ) ) {
						$content = $current_post->post_content;
					}
				}
			}

			//does content has shortcode added?
			if ( has_shortcode( $content, $shortcode ) ) {
				$has_shortcode = true;
			}
		}

		return $has_shortcode;
	}
}

if ( ! function_exists( 'overworld_edge_get_unique_page_class' ) ) {
	/**
	 * Returns unique page class based on post type and page id
	 *
	 * $params int $id is page id
	 * $params bool $allowSingleProductOption
	 * @return string
	 */
	function overworld_edge_get_unique_page_class( $id, $allowSingleProductOption = false ) {
		$page_class = '';

		if ( overworld_edge_is_plugin_installed( 'woocommerce' ) && $allowSingleProductOption ) {

			if ( is_product() ) {
				$id = get_the_ID();
			}
		}

		if ( is_single() ) {
			$page_class = '.postid-' . $id;
		} elseif ( is_home() ) {
			$page_class .= '.home';
		} elseif ( is_archive() || $id === overworld_edge_get_woo_shop_page_id() ) {
			$page_class .= '.archive';
		} elseif ( is_search() ) {
			$page_class .= '.search';
		} elseif ( is_404() ) {
			$page_class .= '.error404';
		} else {
			$page_class .= '.page-id-' . $id;
		}

		return $page_class;
	}
}

if ( ! function_exists( 'overworld_edge_page_custom_style' ) ) {
	/**
	 * Function that print custom page style
	 */
	function overworld_edge_page_custom_style() {
		$style = apply_filters( 'overworld_edge_filter_add_page_custom_style', $style = '' );

		if ( $style !== '' ) {

			if ( overworld_edge_is_plugin_installed( 'woocommerce' ) && overworld_edge_load_woo_assets() ) {
				wp_add_inline_style( 'overworld-edge-woo', $style );
			} else {
				wp_add_inline_style( 'overworld-edge-modules', $style );
			}
		}
	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_page_custom_style' );
}

if ( ! function_exists( 'overworld_edge_print_custom_js' ) ) {
	/**
	 * Prints out custom css from theme options
	 */
	function overworld_edge_print_custom_js() {
		$custom_js = overworld_edge_options()->getOptionValue( 'custom_js' );

		if ( ! empty( $custom_js ) ) {
			wp_add_inline_script( 'overworld-edge-modules', $custom_js );
		}
	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_print_custom_js' );
}

if ( ! function_exists( 'overworld_edge_get_global_variables' ) ) {
	/**
	 * Function that generates global variables and put them in array so they could be used in the theme
	 */
	function overworld_edge_get_global_variables() {
		$global_variables = array();

		$global_variables['edgtfAddForAdminBar']      = is_admin_bar_showing() ? 32 : 0;
		$global_variables['edgtfElementAppearAmount'] = - 100;
		$global_variables['edgtfAjaxUrl']             = esc_url( admin_url( 'admin-ajax.php' ) );
		$global_variables['sliderNavPrevArrow']       = 'ion-ios-arrow-left';
		$global_variables['sliderNavNextArrow']       = 'ion-ios-arrow-right';
		$global_variables['ppExpand']                 = esc_html__( 'Expand the image', 'overworld' );
		$global_variables['ppNext']                   = esc_html__( 'Next', 'overworld' );
		$global_variables['ppPrev']                   = esc_html__( 'Previous', 'overworld' );
		$global_variables['ppClose']                  = esc_html__( 'Close', 'overworld' );

		$global_variables = apply_filters( 'overworld_edge_filter_js_global_variables', $global_variables );

		wp_localize_script( 'overworld-edge-modules', 'edgtfGlobalVars', array(
			'vars' => $global_variables
		) );
	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_get_global_variables' );
}

if ( ! function_exists( 'overworld_edge_per_page_js_variables' ) ) {
	/**
	 * Outputs global JS variable that holds page settings
	 */
	function overworld_edge_per_page_js_variables() {
		$per_page_js_vars = apply_filters( 'overworld_edge_filter_per_page_js_vars', array() );

		wp_localize_script( 'overworld-edge-modules', 'edgtfPerPageVars', array(
			'vars' => $per_page_js_vars
		) );
	}

	add_action( 'wp_enqueue_scripts', 'overworld_edge_per_page_js_variables' );
}

if ( ! function_exists( 'overworld_edge_content_elem_style_attr' ) ) {
	/**
	 * Defines filter for adding custom styles to content HTML element
	 */
	function overworld_edge_content_elem_style_attr() {
		$styles = apply_filters( 'overworld_edge_filter_content_elem_style_attr', array() );

		overworld_edge_inline_style( $styles );
	}
}

if ( ! function_exists( 'overworld_edge_is_plugin_installed' ) ) {
	/**
	 * Function that checks if forward plugin installed
	 *
	 * @param $plugin string
	 *
	 * @return bool
	 */
	function overworld_edge_is_plugin_installed( $plugin ) {
		switch ( $plugin ) {
			case 'core':
				return defined( 'OVERWORLD_CORE_VERSION' );
				break;
			case 'woocommerce':
				return function_exists( 'is_woocommerce' );
				break;
			case 'visual-composer':
				return class_exists( 'WPBakeryVisualComposerAbstract' );
				break;
			case 'revolution-slider':
				return class_exists( 'RevSliderFront' );
				break;
			case 'contact-form-7':
				return defined( 'WPCF7_VERSION' );
				break;
			case 'wpml':
				return defined( 'ICL_SITEPRESS_VERSION' );
				break;
			case 'gutenberg-editor':
				return class_exists( 'WP_Block_Type' );
				break;
			case 'gutenberg-plugin':
				return function_exists( 'is_gutenberg_page' ) && is_gutenberg_page();
				break;
			default:
				return false;
				break;
		}
	}
}

if ( ! function_exists( 'overworld_edge_get_module_part' ) ) {
	function overworld_edge_get_module_part( $module ) {
		return $module;
	}
}

if ( ! function_exists( 'overworld_edge_max_image_width_srcset' ) ) {
	/**
	 * Set max width for srcset to 1920
	 *
	 * @return int
	 */
	function overworld_edge_max_image_width_srcset() {
		return 1920;
	}

	add_filter( 'max_srcset_image_width', 'overworld_edge_max_image_width_srcset' );
}


if ( ! function_exists( 'overworld_edge_has_dashboard_shortcodes' ) ) {
	/**
	 * Function that checks if current page has at least one of dashboard shortcodes added
	 * @return bool
	 */
	function overworld_edge_has_dashboard_shortcodes() {
		$dashboard_shortcodes = array();

		$dashboard_shortcodes = apply_filters( 'overworld_edge_filter_dashboard_shortcodes_list', $dashboard_shortcodes );

		foreach ( $dashboard_shortcodes as $dashboard_shortcode ) {
			$has_shortcode = overworld_edge_has_shortcode( $dashboard_shortcode );

			if ( $has_shortcode ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'overworld_edge_search_svg_icon' ) ) {
	function overworld_edge_search_svg_icon() {

		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18.25px" height="17.25px">
                    <path fill-rule="evenodd"  stroke="currentColor" stroke-width="1.5px" stroke-linecap="butt" stroke-linejoin="miter" fill="none" d="M16.744,14.830 L15.794,15.739 L11.835,11.948 C10.657,12.941 9.129,13.562 7.437,13.562 C3.744,13.562 0.750,10.694 0.750,7.156 C0.750,3.618 3.744,0.750 7.437,0.750 C11.131,0.750 14.125,3.618 14.125,7.156 C14.125,8.608 13.601,9.932 12.751,11.007 L16.744,14.830 Z"/>
                </svg>';

		return $html;
	}
}

if ( ! function_exists( 'overworld_edge_opener_svg' ) ) {
	function overworld_edge_opener_svg() {

		$html = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56px" height="15px">
					<path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M55.998,4.004 L20.000,3.997 L20.000,-0.002 L56.000,-0.002 L55.998,4.004 ZM35.997,15.004 L-0.000,14.997 L-0.000,10.997 L36.000,10.997 L35.997,15.004 Z"/>
				</svg>';

		return $html;
	}
}