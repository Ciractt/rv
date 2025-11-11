<?php

if ( ! function_exists( 'overworld_edge_general_options_map' ) ) {
	/**
	 * General options page
	 */
	function overworld_edge_general_options_map() {

		overworld_edge_add_admin_page(
			array(
				'slug'  => '',
				'title' => esc_html__( 'General', 'overworld' ),
				'icon'  => 'fa fa-institution'
			)
		);

		$panel_design_style = overworld_edge_add_admin_panel(
			array(
				'page'  => '',
				'name'  => 'panel_design_style',
				'title' => esc_html__( 'Design Style', 'overworld' )
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'enable_google_fonts',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Enable Google Fonts', 'overworld' ),
				'parent'        => $panel_design_style
			)
		);

		$google_fonts_container = overworld_edge_add_admin_container(
			array(
				'parent'     => $panel_design_style,
				'name'       => 'google_fonts_container',
				'dependency' => array(
					'hide' => array(
						'enable_google_fonts' => 'no'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'google_fonts',
				'type'          => 'font',
				'default_value' => '-1',
				'label'         => esc_html__( 'Google Font Family', 'overworld' ),
				'description'   => esc_html__( 'Choose a default Google font for your site', 'overworld' ),
				'parent'        => $google_fonts_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'additional_google_fonts',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Additional Google Fonts', 'overworld' ),
				'parent'        => $google_fonts_container
			)
		);

		$additional_google_fonts_container = overworld_edge_add_admin_container(
			array(
				'parent'     => $google_fonts_container,
				'name'       => 'additional_google_fonts_container',
				'dependency' => array(
					'show' => array(
						'additional_google_fonts' => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'additional_google_font1',
				'type'          => 'font',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
				'description'   => esc_html__( 'Choose additional Google font for your site', 'overworld' ),
				'parent'        => $additional_google_fonts_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'additional_google_font2',
				'type'          => 'font',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
				'description'   => esc_html__( 'Choose additional Google font for your site', 'overworld' ),
				'parent'        => $additional_google_fonts_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'additional_google_font3',
				'type'          => 'font',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
				'description'   => esc_html__( 'Choose additional Google font for your site', 'overworld' ),
				'parent'        => $additional_google_fonts_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'additional_google_font4',
				'type'          => 'font',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
				'description'   => esc_html__( 'Choose additional Google font for your site', 'overworld' ),
				'parent'        => $additional_google_fonts_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'additional_google_font5',
				'type'          => 'font',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
				'description'   => esc_html__( 'Choose additional Google font for your site', 'overworld' ),
				'parent'        => $additional_google_fonts_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'google_font_weight',
				'type'          => 'checkboxgroup',
				'default_value' => '',
				'label'         => esc_html__( 'Google Fonts Style & Weight', 'overworld' ),
				'description'   => esc_html__( 'Choose a default Google font weights for your site. Impact on page load time', 'overworld' ),
				'parent'        => $google_fonts_container,
				'options'       => array(
					'100'  => esc_html__( '100 Thin', 'overworld' ),
					'100i' => esc_html__( '100 Thin Italic', 'overworld' ),
					'200'  => esc_html__( '200 Extra-Light', 'overworld' ),
					'200i' => esc_html__( '200 Extra-Light Italic', 'overworld' ),
					'300'  => esc_html__( '300 Light', 'overworld' ),
					'300i' => esc_html__( '300 Light Italic', 'overworld' ),
					'400'  => esc_html__( '400 Regular', 'overworld' ),
					'400i' => esc_html__( '400 Regular Italic', 'overworld' ),
					'500'  => esc_html__( '500 Medium', 'overworld' ),
					'500i' => esc_html__( '500 Medium Italic', 'overworld' ),
					'600'  => esc_html__( '600 Semi-Bold', 'overworld' ),
					'600i' => esc_html__( '600 Semi-Bold Italic', 'overworld' ),
					'700'  => esc_html__( '700 Bold', 'overworld' ),
					'700i' => esc_html__( '700 Bold Italic', 'overworld' ),
					'800'  => esc_html__( '800 Extra-Bold', 'overworld' ),
					'800i' => esc_html__( '800 Extra-Bold Italic', 'overworld' ),
					'900'  => esc_html__( '900 Ultra-Bold', 'overworld' ),
					'900i' => esc_html__( '900 Ultra-Bold Italic', 'overworld' )
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'google_font_subset',
				'type'          => 'checkboxgroup',
				'default_value' => '',
				'label'         => esc_html__( 'Google Fonts Subset', 'overworld' ),
				'description'   => esc_html__( 'Choose a default Google font subsets for your site', 'overworld' ),
				'parent'        => $google_fonts_container,
				'options'       => array(
					'latin'        => esc_html__( 'Latin', 'overworld' ),
					'latin-ext'    => esc_html__( 'Latin Extended', 'overworld' ),
					'cyrillic'     => esc_html__( 'Cyrillic', 'overworld' ),
					'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'overworld' ),
					'greek'        => esc_html__( 'Greek', 'overworld' ),
					'greek-ext'    => esc_html__( 'Greek Extended', 'overworld' ),
					'vietnamese'   => esc_html__( 'Vietnamese', 'overworld' )
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'first_color',
				'type'        => 'color',
				'label'       => esc_html__( 'First Main Color', 'overworld' ),
				'description' => esc_html__( 'Choose the most dominant theme color. Default color is #00bbb3', 'overworld' ),
				'parent'      => $panel_design_style
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'page_background_color',
				'type'        => 'color',
				'label'       => esc_html__( 'Page Background Color', 'overworld' ),
				'description' => esc_html__( 'Choose the background color for page content. Default color is #ffffff', 'overworld' ),
				'parent'      => $panel_design_style
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'page_background_image',
				'type'        => 'image',
				'label'       => esc_html__( 'Page Background Image', 'overworld' ),
				'description' => esc_html__( 'Choose the background image for page content', 'overworld' ),
				'parent'      => $panel_design_style
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'page_background_image_repeat',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Page Background Image Repeat', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will set the background image as a repeating pattern throughout the page, otherwise the image will appear as the cover background image', 'overworld' ),
				'parent'        => $panel_design_style
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'selection_color',
				'type'        => 'color',
				'label'       => esc_html__( 'Text Selection Color', 'overworld' ),
				'description' => esc_html__( 'Choose the color users see when selecting text', 'overworld' ),
				'parent'      => $panel_design_style
			)
		);

		/***************** Passepartout Layout - begin **********************/

		overworld_edge_add_admin_field(
			array(
				'name'          => 'boxed',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Boxed Layout', 'overworld' ),
				'parent'        => $panel_design_style
			)
		);

		$boxed_container = overworld_edge_add_admin_container(
			array(
				'parent'     => $panel_design_style,
				'name'       => 'boxed_container',
				'dependency' => array(
					'show' => array(
						'boxed' => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'page_background_color_in_box',
				'type'        => 'color',
				'label'       => esc_html__( 'Page Background Color', 'overworld' ),
				'description' => esc_html__( 'Choose the page background color outside box', 'overworld' ),
				'parent'      => $boxed_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'boxed_background_image',
				'type'        => 'image',
				'label'       => esc_html__( 'Background Image', 'overworld' ),
				'description' => esc_html__( 'Choose an image to be displayed in background', 'overworld' ),
				'parent'      => $boxed_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'boxed_pattern_background_image',
				'type'        => 'image',
				'label'       => esc_html__( 'Background Pattern', 'overworld' ),
				'description' => esc_html__( 'Choose an image to be used as background pattern', 'overworld' ),
				'parent'      => $boxed_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'boxed_background_image_attachment',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Background Image Attachment', 'overworld' ),
				'description'   => esc_html__( 'Choose background image attachment', 'overworld' ),
				'parent'        => $boxed_container,
				'options'       => array(
					''       => esc_html__( 'Default', 'overworld' ),
					'fixed'  => esc_html__( 'Fixed', 'overworld' ),
					'scroll' => esc_html__( 'Scroll', 'overworld' )
				)
			)
		);

		/***************** Boxed Layout - end **********************/

		/***************** Passepartout Layout - begin **********************/

		overworld_edge_add_admin_field(
			array(
				'name'          => 'paspartu',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Passepartout', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will display passepartout around site content', 'overworld' ),
				'parent'        => $panel_design_style
			)
		);

		$paspartu_container = overworld_edge_add_admin_container(
			array(
				'parent'     => $panel_design_style,
				'name'       => 'paspartu_container',
				'dependency' => array(
					'show' => array(
						'paspartu' => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'paspartu_color',
				'type'        => 'color',
				'label'       => esc_html__( 'Passepartout Color', 'overworld' ),
				'description' => esc_html__( 'Choose passepartout color, default value is #ffffff', 'overworld' ),
				'parent'      => $paspartu_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'paspartu_width',
				'type'        => 'text',
				'label'       => esc_html__( 'Passepartout Size', 'overworld' ),
				'description' => esc_html__( 'Enter size amount for passepartout', 'overworld' ),
				'parent'      => $paspartu_container,
				'args'        => array(
					'col_width' => 2,
					'suffix'    => 'px or %'
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'paspartu_responsive_width',
				'type'        => 'text',
				'label'       => esc_html__( 'Responsive Passepartout Size', 'overworld' ),
				'description' => esc_html__( 'Enter size amount for passepartout for smaller screens (tablets and mobiles view)', 'overworld' ),
				'parent'      => $paspartu_container,
				'args'        => array(
					'col_width' => 2,
					'suffix'    => 'px or %'
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $paspartu_container,
				'type'          => 'yesno',
				'default_value' => 'no',
				'name'          => 'disable_top_paspartu',
				'label'         => esc_html__( 'Disable Top Passepartout', 'overworld' )
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $paspartu_container,
				'type'          => 'yesno',
				'default_value' => 'no',
				'name'          => 'enable_fixed_paspartu',
				'label'         => esc_html__( 'Enable Fixed Passepartout', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will set fixed passepartout for your screens', 'overworld' )
			)
		);

		/***************** Passepartout Layout - end **********************/

		/***************** Content Layout - begin **********************/

		overworld_edge_add_admin_field(
			array(
				'name'          => 'initial_content_width',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Initial Width of Content', 'overworld' ),
				'description'   => esc_html__( 'Choose the initial width of content which is in grid (Applies to pages set to "Default Template" and rows set to "In Grid")', 'overworld' ),
				'parent'        => $panel_design_style,
				'options'       => array(
					'edgtf-grid-1100' => esc_html__( '1100px - default', 'overworld' ),
					'edgtf-grid-1300' => esc_html__( '1300px', 'overworld' ),
					'edgtf-grid-1200' => esc_html__( '1200px', 'overworld' ),
					'edgtf-grid-1000' => esc_html__( '1000px', 'overworld' ),
					'edgtf-grid-800'  => esc_html__( '800px', 'overworld' )
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'preload_pattern_image',
				'type'        => 'image',
				'label'       => esc_html__( 'Preload Pattern Image', 'overworld' ),
				'description' => esc_html__( 'Choose preload pattern image to be displayed until images are loaded', 'overworld' ),
				'parent'      => $panel_design_style
			)
		);

		/***************** Content Layout - end **********************/

		$panel_settings = overworld_edge_add_admin_panel(
			array(
				'page'  => '',
				'name'  => 'panel_settings',
				'title' => esc_html__( 'Settings', 'overworld' )
			)
		);

		/***************** Smooth Scroll Layout - begin **********************/

		overworld_edge_add_admin_field(
			array(
				'name'          => 'page_smooth_scroll',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Smooth Scroll', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will perform a smooth scrolling effect on every page (except on Mac and touch devices)', 'overworld' ),
				'parent'        => $panel_settings
			)
		);

		/***************** Smooth Scroll Layout - end **********************/

		/***************** Smooth Page Transitions Layout - begin **********************/

		overworld_edge_add_admin_field(
			array(
				'name'          => 'smooth_page_transitions',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Smooth Page Transitions', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will perform a smooth transition between pages when clicking on links', 'overworld' ),
				'parent'        => $panel_settings
			)
		);

		$page_transitions_container = overworld_edge_add_admin_container(
			array(
				'parent'     => $panel_settings,
				'name'       => 'page_transitions_container',
				'dependency' => array(
					'show' => array(
						'smooth_page_transitions' => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'page_transition_preloader',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Preloading Animation', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will display an animated preloader while the page content is loading', 'overworld' ),
				'parent'        => $page_transitions_container
			)
		);

		$page_transition_preloader_container = overworld_edge_add_admin_container(
			array(
				'parent'     => $page_transitions_container,
				'name'       => 'page_transition_preloader_container',
				'dependency' => array(
					'show' => array(
						'page_transition_preloader' => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'   => 'smooth_pt_bgnd_color',
				'type'   => 'color',
				'label'  => esc_html__( 'Page Loader Background Color', 'overworld' ),
				'parent' => $page_transition_preloader_container
			)
		);

		$group_pt_spinner_animation = overworld_edge_add_admin_group(
			array(
				'name'        => 'group_pt_spinner_animation',
				'title'       => esc_html__( 'Loader Style', 'overworld' ),
				'description' => esc_html__( 'Define styles for loader spinner animation', 'overworld' ),
				'parent'      => $page_transition_preloader_container
			)
		);

		$row_pt_spinner_animation = overworld_edge_add_admin_row(
			array(
				'name'   => 'row_pt_spinner_animation',
				'parent' => $group_pt_spinner_animation
			)
		);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'selectsimple',
				'name'          => 'smooth_pt_spinner_type',
				'default_value' => '',
				'label'         => esc_html__( 'Spinner Type', 'overworld' ),
				'parent'        => $row_pt_spinner_animation,
				'options'       => array(
					'overworld_spinner'     => esc_html__( 'Overworld Spinner', 'overworld' ),
					'rotate_circles'        => esc_html__( 'Rotate Circles', 'overworld' ),
					'pulse'                 => esc_html__( 'Pulse', 'overworld' ),
					'double_pulse'          => esc_html__( 'Double Pulse', 'overworld' ),
					'cube'                  => esc_html__( 'Cube', 'overworld' ),
					'rotating_cubes'        => esc_html__( 'Rotating Cubes', 'overworld' ),
					'stripes'               => esc_html__( 'Stripes', 'overworld' ),
					'wave'                  => esc_html__( 'Wave', 'overworld' ),
					'two_rotating_circles'  => esc_html__( '2 Rotating Circles', 'overworld' ),
					'five_rotating_circles' => esc_html__( '5 Rotating Circles', 'overworld' ),
					'atom'                  => esc_html__( 'Atom', 'overworld' ),
					'clock'                 => esc_html__( 'Clock', 'overworld' ),
					'mitosis'               => esc_html__( 'Mitosis', 'overworld' ),
					'lines'                 => esc_html__( 'Lines', 'overworld' ),
					'fussion'               => esc_html__( 'Fussion', 'overworld' ),
					'wave_circles'          => esc_html__( 'Wave Circles', 'overworld' ),
					'pulse_circles'         => esc_html__( 'Pulse Circles', 'overworld' )
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'colorsimple',
				'name'          => 'smooth_pt_spinner_color',
				'default_value' => '',
				'label'         => esc_html__( 'Spinner Color', 'overworld' ),
				'parent'        => $row_pt_spinner_animation
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'pt_loading_logo',
				'type'        => 'image',
				'label'       => esc_html__( 'Preloader Logo Image', 'overworld' ),
				'description' => esc_html__( 'Choose preloader logo image to be displayed until the page is loaded', 'overworld' ),
				'parent'      => $row_pt_spinner_animation,
				'dependency'  => array(
					'show' => array(
						'smooth_pt_spinner_type' => 'overworld_spinner'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'page_transition_fadeout',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Fade Out Animation', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will turn on fade out animation when leaving page', 'overworld' ),
				'parent'        => $page_transitions_container
			)
		);

		/***************** Smooth Page Transitions Layout - end **********************/

		overworld_edge_add_admin_field(
			array(
				'name'          => 'show_back_button',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show "Back To Top Button"', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will display a Back to Top button on every page', 'overworld' ),
				'parent'        => $panel_settings
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'responsiveness',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Responsiveness', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will make all pages responsive', 'overworld' ),
				'parent'        => $panel_settings
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'custom_cursor',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Custom Cursor', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will display custom cursor', 'overworld' ),
				'parent'        => $panel_settings
			)
		);

		$panel_custom_code = overworld_edge_add_admin_panel(
			array(
				'page'  => '',
				'name'  => 'panel_custom_code',
				'title' => esc_html__( 'Custom Code', 'overworld' )
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'custom_js',
				'type'        => 'textarea',
				'label'       => esc_html__( 'Custom JS', 'overworld' ),
				'description' => esc_html__( 'Enter your custom Javascript here', 'overworld' ),
				'parent'      => $panel_custom_code
			)
		);

		$panel_google_api = overworld_edge_add_admin_panel(
			array(
				'page'  => '',
				'name'  => 'panel_google_api',
				'title' => esc_html__( 'Google API', 'overworld' )
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'google_maps_api_key',
				'type'        => 'text',
				'label'       => esc_html__( 'Google Maps Api Key', 'overworld' ),
				'description' => esc_html__( 'Insert your Google Maps API key here. For instructions on how to create a Google Maps API key, please refer to our to our documentation.', 'overworld' ),
				'parent'      => $panel_google_api
			)
		);
	}

	add_action( 'overworld_edge_action_options_map', 'overworld_edge_general_options_map', overworld_edge_set_options_map_position( 'general' ) );
}

if ( ! function_exists( 'overworld_edge_page_general_style' ) ) {
	/**
	 * Function that prints page general inline styles
	 */
	function overworld_edge_page_general_style( $style ) {
		$current_style = '';
		$page_id       = overworld_edge_get_page_id();
		$class_prefix  = overworld_edge_get_unique_page_class( $page_id );

		$boxed_background_style = array();

		$boxed_page_background_color = overworld_edge_get_meta_field_intersect( 'page_background_color_in_box', $page_id );
		if ( ! empty( $boxed_page_background_color ) ) {
			$boxed_background_style['background-color'] = $boxed_page_background_color;
		}

		$boxed_page_background_image = overworld_edge_get_meta_field_intersect( 'boxed_background_image', $page_id );
		if ( ! empty( $boxed_page_background_image ) ) {
			$boxed_background_style['background-image']    = 'url(' . esc_url( $boxed_page_background_image ) . ')';
			$boxed_background_style['background-position'] = 'center 0px';
			$boxed_background_style['background-repeat']   = 'no-repeat';
		}

		$boxed_page_background_pattern_image = overworld_edge_get_meta_field_intersect( 'boxed_pattern_background_image', $page_id );
		if ( ! empty( $boxed_page_background_pattern_image ) ) {
			$boxed_background_style['background-image']    = 'url(' . esc_url( $boxed_page_background_pattern_image ) . ')';
			$boxed_background_style['background-position'] = '0px 0px';
			$boxed_background_style['background-repeat']   = 'repeat';
		}

		$boxed_page_background_attachment = overworld_edge_get_meta_field_intersect( 'boxed_background_image_attachment', $page_id );
		if ( ! empty( $boxed_page_background_attachment ) ) {
			$boxed_background_style['background-attachment'] = $boxed_page_background_attachment;
		}

		$boxed_background_selector = $class_prefix . '.edgtf-boxed .edgtf-wrapper';

		if ( ! empty( $boxed_background_style ) ) {
			$current_style .= overworld_edge_dynamic_css( $boxed_background_selector, $boxed_background_style );
		}

		$paspartu_style     = array();
		$paspartu_res_style = array();
		$paspartu_res_start = '@media only screen and (max-width: 1024px) {';
		$paspartu_res_end   = '}';

		$paspartu_header_selector        = array(
			'.edgtf-paspartu-enabled .edgtf-page-header .edgtf-fixed-wrapper.fixed',
			'.edgtf-paspartu-enabled .edgtf-sticky-header',
			'.edgtf-paspartu-enabled .edgtf-mobile-header.mobile-header-appear .edgtf-mobile-header-inner'
		);
		$paspartu_header_appear_selector = array(
			'.edgtf-paspartu-enabled.edgtf-fixed-paspartu-enabled .edgtf-page-header .edgtf-fixed-wrapper.fixed',
			'.edgtf-paspartu-enabled.edgtf-fixed-paspartu-enabled .edgtf-sticky-header.header-appear',
			'.edgtf-paspartu-enabled.edgtf-fixed-paspartu-enabled .edgtf-mobile-header.mobile-header-appear .edgtf-mobile-header-inner'
		);

		$paspartu_header_style                   = array();
		$paspartu_header_appear_style            = array();
		$paspartu_header_responsive_style        = array();
		$paspartu_header_appear_responsive_style = array();

		$paspartu_color = overworld_edge_get_meta_field_intersect( 'paspartu_color', $page_id );
		if ( ! empty( $paspartu_color ) ) {
			$paspartu_style['background-color'] = $paspartu_color;
		}

		$paspartu_width = overworld_edge_get_meta_field_intersect( 'paspartu_width', $page_id );
		if ( $paspartu_width !== '' ) {
			if ( overworld_edge_string_ends_with( $paspartu_width, '%' ) || overworld_edge_string_ends_with( $paspartu_width, 'px' ) ) {
				$paspartu_style['padding'] = $paspartu_width;

				$paspartu_clean_width      = overworld_edge_string_ends_with( $paspartu_width, '%' ) ? overworld_edge_filter_suffix( $paspartu_width, '%' ) : overworld_edge_filter_suffix( $paspartu_width, 'px' );
				$paspartu_clean_width_mark = overworld_edge_string_ends_with( $paspartu_width, '%' ) ? '%' : 'px';

				$paspartu_header_style['left']              = $paspartu_width;
				$paspartu_header_style['width']             = 'calc(100% - ' . ( 2 * $paspartu_clean_width ) . $paspartu_clean_width_mark . ')';
				$paspartu_header_appear_style['margin-top'] = $paspartu_width;
			} else {
				$paspartu_style['padding'] = $paspartu_width . 'px';

				$paspartu_header_style['left']              = $paspartu_width . 'px';
				$paspartu_header_style['width']             = 'calc(100% - ' . ( 2 * $paspartu_width ) . 'px)';
				$paspartu_header_appear_style['margin-top'] = $paspartu_width . 'px';
			}
		}

		$paspartu_selector = $class_prefix . '.edgtf-paspartu-enabled .edgtf-wrapper';

		if ( ! empty( $paspartu_style ) ) {
			$current_style .= overworld_edge_dynamic_css( $paspartu_selector, $paspartu_style );
		}

		if ( ! empty( $paspartu_header_style ) ) {
			$current_style .= overworld_edge_dynamic_css( $paspartu_header_selector, $paspartu_header_style );
			$current_style .= overworld_edge_dynamic_css( $paspartu_header_appear_selector, $paspartu_header_appear_style );
		}

		$paspartu_responsive_width = overworld_edge_get_meta_field_intersect( 'paspartu_responsive_width', $page_id );
		if ( $paspartu_responsive_width !== '' ) {
			if ( overworld_edge_string_ends_with( $paspartu_responsive_width, '%' ) || overworld_edge_string_ends_with( $paspartu_responsive_width, 'px' ) ) {
				$paspartu_res_style['padding'] = $paspartu_responsive_width;

				$paspartu_clean_width      = overworld_edge_string_ends_with( $paspartu_responsive_width, '%' ) ? overworld_edge_filter_suffix( $paspartu_responsive_width, '%' ) : overworld_edge_filter_suffix( $paspartu_responsive_width, 'px' );
				$paspartu_clean_width_mark = overworld_edge_string_ends_with( $paspartu_responsive_width, '%' ) ? '%' : 'px';

				$paspartu_header_responsive_style['left']              = $paspartu_responsive_width;
				$paspartu_header_responsive_style['width']             = 'calc(100% - ' . ( 2 * $paspartu_clean_width ) . $paspartu_clean_width_mark . ')';
				$paspartu_header_appear_responsive_style['margin-top'] = $paspartu_responsive_width;
			} else {
				$paspartu_res_style['padding'] = $paspartu_responsive_width . 'px';

				$paspartu_header_responsive_style['left']              = $paspartu_responsive_width . 'px';
				$paspartu_header_responsive_style['width']             = 'calc(100% - ' . ( 2 * $paspartu_responsive_width ) . 'px)';
				$paspartu_header_appear_responsive_style['margin-top'] = $paspartu_responsive_width . 'px';
			}
		}

		if ( ! empty( $paspartu_res_style ) ) {
			$current_style .= $paspartu_res_start . overworld_edge_dynamic_css( $paspartu_selector, $paspartu_res_style ) . $paspartu_res_end;
		}

		if ( ! empty( $paspartu_header_responsive_style ) ) {
			$current_style .= $paspartu_res_start . overworld_edge_dynamic_css( $paspartu_header_selector, $paspartu_header_responsive_style ) . $paspartu_res_end;
			$current_style .= $paspartu_res_start . overworld_edge_dynamic_css( $paspartu_header_appear_selector, $paspartu_header_appear_responsive_style ) . $paspartu_res_end;
		}

		$current_style = $current_style . $style;

		return $current_style;
	}

	add_filter( 'overworld_edge_filter_add_page_custom_style', 'overworld_edge_page_general_style' );
}