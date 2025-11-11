<?php

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
if ( function_exists( 'vc_set_as_theme' ) ) {
	vc_set_as_theme( true );
}

/**
 * Change path for overridden templates
 */
if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
	$dir = OVERWORLD_EDGE_ROOT_DIR . '/vc-templates';
	vc_set_shortcodes_templates_dir( $dir );
}

if ( ! function_exists( 'overworld_edge_configure_visual_composer_frontend_editor' ) ) {
	/**
	 * Configuration for Visual Composer FrontEnd Editor
	 * Hooks on vc_after_init action
	 */
	function overworld_edge_configure_visual_composer_frontend_editor() {
		/**
		 * Remove frontend editor
		 */
		if ( function_exists( 'vc_disable_frontend' ) ) {
			vc_disable_frontend();
		}
	}
	
	add_action( 'vc_after_init', 'overworld_edge_configure_visual_composer_frontend_editor' );
}

if ( ! function_exists( 'overworld_edge_vc_row_map' ) ) {
	/**
	 * Map VC Row shortcode
	 * Hooks on vc_after_init action
	 */
	function overworld_edge_vc_row_map() {
		
		/******* VC Row shortcode - begin *******/
		
		vc_add_param( 'vc_row',
			array(
				'type'       => 'dropdown',
				'param_name' => 'row_content_width',
				'heading'    => esc_html__( 'Edge Row Content Width', 'overworld' ),
				'value'      => array(
					esc_html__( 'Full Width', 'overworld' ) => 'full-width',
					esc_html__( 'In Grid', 'overworld' )    => 'grid'
				),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'        => 'textfield',
				'param_name'  => 'anchor',
				'heading'     => esc_html__( 'Edge Anchor ID', 'overworld' ),
				'description' => esc_html__( 'For example "home"', 'overworld' ),
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);

		vc_add_param( 'vc_row',
			array(
				'type'        => 'dropdown',
				'param_name'  => 'enable_box_shadow',
				'heading'     => esc_html__( 'Edge Enable Box Shadow', 'overworld' ),
				'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
				'save_always' => true,
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);

		vc_add_param( 'vc_row',
			array(
				'type'       => 'colorpicker',
				'param_name' => 'simple_background_color',
				'heading'    => esc_html__( 'Edge Background Color', 'overworld' ),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'       => 'attach_image',
				'param_name' => 'simple_background_image',
				'heading'    => esc_html__( 'Edge Background Image', 'overworld' ),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'        => 'textfield',
				'param_name'  => 'background_image_position',
				'heading'     => esc_html__( 'Edge Background Position', 'overworld' ),
				'description' => esc_html__( 'Set the starting position of a background image, default value is top left', 'overworld' ),
				'dependency'  => array( 'element' => 'simple_background_image', 'not_empty' => true ),
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'        => 'dropdown',
				'param_name'  => 'disable_background_image',
				'heading'     => esc_html__( 'Edge Disable Background Image', 'overworld' ),
				'value'       => array(
					esc_html__( 'Never', 'overworld' )        => '',
					esc_html__( 'Below 1280px', 'overworld' ) => '1280',
					esc_html__( 'Below 1024px', 'overworld' ) => '1024',
					esc_html__( 'Below 768px', 'overworld' )  => '768',
					esc_html__( 'Below 680px', 'overworld' )  => '680',
					esc_html__( 'Below 480px', 'overworld' )  => '480'
				),
				'save_always' => true,
				'description' => esc_html__( 'Choose on which stage you hide row background image', 'overworld' ),
				'dependency'  => array( 'element' => 'simple_background_image', 'not_empty' => true ),
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'       => 'attach_image',
				'param_name' => 'parallax_background_image',
				'heading'    => esc_html__( 'Edge Parallax Background Image', 'overworld' ),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'        => 'textfield',
				'param_name'  => 'parallax_bg_speed',
				'heading'     => esc_html__( 'Edge Parallax Speed', 'overworld' ),
				'description' => esc_html__( 'Set your parallax speed. Default value is 1.', 'overworld' ),
				'dependency'  => array( 'element' => 'parallax_background_image', 'not_empty' => true ),
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'       => 'textfield',
				'param_name' => 'parallax_bg_height',
				'heading'    => esc_html__( 'Edge Parallax Section Height (px)', 'overworld' ),
				'dependency' => array( 'element' => 'parallax_background_image', 'not_empty' => true ),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row',
			array(
				'type'       => 'dropdown',
				'param_name' => 'content_text_aligment',
				'heading'    => esc_html__( 'Edge Content Aligment', 'overworld' ),
				'value'      => array(
					esc_html__( 'Default', 'overworld' ) => '',
					esc_html__( 'Left', 'overworld' )    => 'left',
					esc_html__( 'Center', 'overworld' )  => 'center',
					esc_html__( 'Right', 'overworld' )   => 'right'
				),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);

		do_action( 'overworld_edge_action_additional_vc_row_params' );
		
		/******* VC Row shortcode - end *******/
		
		/******* VC Row Inner shortcode - begin *******/
		
		vc_add_param( 'vc_row_inner',
			array(
				'type'       => 'dropdown',
				'param_name' => 'row_content_width',
				'heading'    => esc_html__( 'Edge Row Content Width', 'overworld' ),
				'value'      => array(
					esc_html__( 'Full Width', 'overworld' ) => 'full-width',
					esc_html__( 'In Grid', 'overworld' )    => 'grid'
				),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);

		vc_add_param( 'vc_row_inner',
			array(
				'type'        => 'dropdown',
				'param_name'  => 'enable_box_shadow',
				'heading'     => esc_html__( 'Edge Enable Box Shadow', 'overworld' ),
				'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
				'save_always' => true,
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);

		vc_add_param( 'vc_row_inner',
			array(
				'type'       => 'colorpicker',
				'param_name' => 'simple_background_color',
				'heading'    => esc_html__( 'Edge Background Color', 'overworld' ),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row_inner',
			array(
				'type'       => 'attach_image',
				'param_name' => 'simple_background_image',
				'heading'    => esc_html__( 'Edge Background Image', 'overworld' ),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row_inner',
			array(
				'type'        => 'textfield',
				'param_name'  => 'background_image_position',
				'heading'     => esc_html__( 'Edge Background Position', 'overworld' ),
				'description' => esc_html__( 'Set the starting position of a background image, default value is top left', 'overworld' ),
				'dependency'  => array( 'element' => 'simple_background_image', 'not_empty' => true ),
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row_inner',
			array(
				'type'        => 'dropdown',
				'param_name'  => 'disable_background_image',
				'heading'     => esc_html__( 'Edge Disable Background Image', 'overworld' ),
				'value'       => array(
					esc_html__( 'Never', 'overworld' )        => '',
					esc_html__( 'Below 1280px', 'overworld' ) => '1280',
					esc_html__( 'Below 1024px', 'overworld' ) => '1024',
					esc_html__( 'Below 768px', 'overworld' )  => '768',
					esc_html__( 'Below 680px', 'overworld' )  => '680',
					esc_html__( 'Below 480px', 'overworld' )  => '480'
				),
				'save_always' => true,
				'description' => esc_html__( 'Choose on which stage you hide row background image', 'overworld' ),
				'dependency'  => array( 'element' => 'simple_background_image', 'not_empty' => true ),
				'group'       => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		vc_add_param( 'vc_row_inner',
			array(
				'type'       => 'dropdown',
				'param_name' => 'content_text_aligment',
				'heading'    => esc_html__( 'Edge Content Aligment', 'overworld' ),
				'value'      => array(
					esc_html__( 'Default', 'overworld' ) => '',
					esc_html__( 'Left', 'overworld' )    => 'left',
					esc_html__( 'Center', 'overworld' )  => 'center',
					esc_html__( 'Right', 'overworld' )   => 'right'
				),
				'group'      => esc_html__( 'Edge Settings', 'overworld' )
			)
		);
		
		/******* VC Row Inner shortcode - end *******/
		
		/******* VC Revolution Slider shortcode - begin *******/
		
		if ( overworld_edge_is_plugin_installed( 'revolution-slider' ) ) {
			
			vc_add_param( 'rev_slider_vc',
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_paspartu',
					'heading'     => esc_html__( 'Edge Enable Passepartout', 'overworld' ),
					'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
					'save_always' => true,
					'group'       => esc_html__( 'Edge Settings', 'overworld' )
				)
			);
			
			vc_add_param( 'rev_slider_vc',
				array(
					'type'        => 'dropdown',
					'param_name'  => 'paspartu_size',
					'heading'     => esc_html__( 'Edge Passepartout Size', 'overworld' ),
					'value'       => array(
						esc_html__( 'Tiny', 'overworld' )   => 'tiny',
						esc_html__( 'Small', 'overworld' )  => 'small',
						esc_html__( 'Normal', 'overworld' ) => 'normal',
						esc_html__( 'Large', 'overworld' )  => 'large'
					),
					'save_always' => true,
					'dependency'  => array( 'element' => 'enable_paspartu', 'value' => array( 'yes' ) ),
					'group'       => esc_html__( 'Edge Settings', 'overworld' )
				)
			);
			
			vc_add_param( 'rev_slider_vc',
				array(
					'type'        => 'dropdown',
					'param_name'  => 'disable_side_paspartu',
					'heading'     => esc_html__( 'Edge Disable Side Passepartout', 'overworld' ),
					'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
					'save_always' => true,
					'dependency'  => array( 'element' => 'enable_paspartu', 'value' => array( 'yes' ) ),
					'group'       => esc_html__( 'Edge Settings', 'overworld' )
				)
			);
			
			vc_add_param( 'rev_slider_vc',
				array(
					'type'        => 'dropdown',
					'param_name'  => 'disable_top_paspartu',
					'heading'     => esc_html__( 'Edge Disable Top Passepartout', 'overworld' ),
					'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
					'save_always' => true,
					'dependency'  => array( 'element' => 'enable_paspartu', 'value' => array( 'yes' ) ),
					'group'       => esc_html__( 'Edge Settings', 'overworld' )
				)
			);
		}
		
		/******* VC Revolution Slider shortcode - end *******/
	}
	
	add_action( 'vc_after_init', 'overworld_edge_vc_row_map' );
}