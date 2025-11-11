<?php

if ( ! function_exists( 'overworld_edge_error_404_options_map' ) ) {
	function overworld_edge_error_404_options_map() {
		
		overworld_edge_add_admin_page(
			array(
				'slug'  => '__404_error_page',
				'title' => esc_html__( '404 Error Page', 'overworld' ),
				'icon'  => 'fa fa-exclamation-triangle'
			)
		);
		
		$panel_404_header = overworld_edge_add_admin_panel(
			array(
				'page'  => '__404_error_page',
				'name'  => 'panel_404_header',
				'title' => esc_html__( 'Header', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $panel_404_header,
				'type'        => 'color',
				'name'        => '404_menu_area_background_color_header',
				'label'       => esc_html__( 'Background Color', 'overworld' ),
				'description' => esc_html__( 'Choose a background color for header area', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_404_header,
				'type'          => 'text',
				'name'          => '404_menu_area_background_transparency_header',
				'default_value' => '',
				'label'         => esc_html__( 'Background Transparency', 'overworld' ),
				'description'   => esc_html__( 'Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'overworld' ),
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $panel_404_header,
				'type'        => 'color',
				'name'        => '404_menu_area_border_color_header',
				'label'       => esc_html__( 'Border Color', 'overworld' ),
				'description' => esc_html__( 'Choose a border bottom color for header area', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_404_header,
				'type'          => 'select',
				'name'          => '404_header_style',
				'default_value' => '',
				'label'         => esc_html__( 'Header Skin', 'overworld' ),
				'description'   => esc_html__( 'Choose a header style to make header elements (logo, main menu, side menu button) in that predefined style', 'overworld' ),
				'options'       => array(
					''             => esc_html__( 'Default', 'overworld' ),
					'light-header' => esc_html__( 'Light', 'overworld' ),
					'dark-header'  => esc_html__( 'Dark', 'overworld' )
				)
			)
		);
		
		$panel_404_options = overworld_edge_add_admin_panel(
			array(
				'page'  => '__404_error_page',
				'name'  => 'panel_404_options',
				'title' => esc_html__( '404 Page Options', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent' => $panel_404_options,
				'type'   => 'color',
				'name'   => '404_page_background_color',
				'label'  => esc_html__( 'Background Color', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $panel_404_options,
				'type'        => 'image',
				'name'        => '404_page_background_image',
				'label'       => esc_html__( 'Background Image', 'overworld' ),
				'description' => esc_html__( 'Choose a background image for 404 page', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $panel_404_options,
				'type'        => 'image',
				'name'        => '404_page_background_pattern_image',
				'label'       => esc_html__( 'Pattern Background Image', 'overworld' ),
				'description' => esc_html__( 'Choose a pattern image for 404 page', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $panel_404_options,
				'type'        => 'image',
				'name'        => '404_page_title_image',
				'label'       => esc_html__( 'Title Image', 'overworld' ),
				'description' => esc_html__( 'Choose a background image for displaying above 404 page Title', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_404_options,
				'type'          => 'text',
				'name'          => '404_title',
				'default_value' => '',
				'label'         => esc_html__( 'Title', 'overworld' ),
				'description'   => esc_html__( 'Enter title for 404 page. Default label is "404".', 'overworld' )
			)
		);
		
		$first_level_group = overworld_edge_add_admin_group(
			array(
				'parent'      => $panel_404_options,
				'name'        => 'first_level_group',
				'title'       => esc_html__( 'Title Style', 'overworld' ),
				'description' => esc_html__( 'Define styles for 404 page title', 'overworld' )
			)
		);
		
		$first_level_row1 = overworld_edge_add_admin_row(
			array(
				'parent' => $first_level_group,
				'name'   => 'first_level_row1'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent' => $first_level_row1,
				'type'   => 'colorsimple',
				'name'   => '404_title_color',
				'label'  => esc_html__( 'Text Color', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row1,
				'type'          => 'fontsimple',
				'name'          => '404_title_google_fonts',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row1,
				'type'          => 'textsimple',
				'name'          => '404_title_font_size',
				'default_value' => '',
				'label'         => esc_html__( 'Font Size', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row1,
				'type'          => 'textsimple',
				'name'          => '404_title_line_height',
				'default_value' => '',
				'label'         => esc_html__( 'Line Height', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		$first_level_row2 = overworld_edge_add_admin_row(
			array(
				'parent' => $first_level_group,
				'name'   => 'first_level_row2',
				'next'   => true
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_title_font_style',
				'default_value' => '',
				'label'         => esc_html__( 'Font Style', 'overworld' ),
				'options'       => overworld_edge_get_font_style_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_title_font_weight',
				'default_value' => '',
				'label'         => esc_html__( 'Font Weight', 'overworld' ),
				'options'       => overworld_edge_get_font_weight_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row2,
				'type'          => 'textsimple',
				'name'          => '404_title_letter_spacing',
				'default_value' => '',
				'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $first_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_title_text_transform',
				'default_value' => '',
				'label'         => esc_html__( 'Text Transform', 'overworld' ),
				'options'       => overworld_edge_get_text_transform_array()
			)
		);

        $first_level_group_responsive = overworld_edge_add_admin_group(
            array(
                'parent'      => $panel_404_options,
                'name'        => 'first_level_group_responsive',
                'title'       => esc_html__( 'Title Style Responsive', 'overworld' ),
                'description' => esc_html__( 'Define responsive styles for 404 page title (under 680px)', 'overworld' )
            )
        );

        $first_level_row3 = overworld_edge_add_admin_row(
            array(
                'parent' => $first_level_group_responsive,
                'name'   => 'first_level_row3'
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $first_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_title_responsive_font_size',
                'default_value' => '',
                'label'         => esc_html__( 'Font Size', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $first_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_title_responsive_line_height',
                'default_value' => '',
                'label'         => esc_html__( 'Line Height', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $first_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_title_responsive_letter_spacing',
                'default_value' => '',
                'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_404_options,
				'type'          => 'text',
				'name'          => '404_subtitle',
				'default_value' => '',
				'label'         => esc_html__( 'Subtitle', 'overworld' ),
				'description'   => esc_html__( 'Enter subtitle for 404 page. Default label is "PAGE NOT FOUND".', 'overworld' )
			)
		);
		
		$second_level_group = overworld_edge_add_admin_group(
			array(
				'parent'      => $panel_404_options,
				'name'        => 'second_level_group',
				'title'       => esc_html__( 'Subtitle Style', 'overworld' ),
				'description' => esc_html__( 'Define styles for 404 page subtitle', 'overworld' )
			)
		);
		
		$second_level_row1 = overworld_edge_add_admin_row(
			array(
				'parent' => $second_level_group,
				'name'   => 'second_level_row1'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent' => $second_level_row1,
				'type'   => 'colorsimple',
				'name'   => '404_subtitle_color',
				'label'  => esc_html__( 'Text Color', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row1,
				'type'          => 'fontsimple',
				'name'          => '404_subtitle_google_fonts',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row1,
				'type'          => 'textsimple',
				'name'          => '404_subtitle_font_size',
				'default_value' => '',
				'label'         => esc_html__( 'Font Size', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row1,
				'type'          => 'textsimple',
				'name'          => '404_subtitle_line_height',
				'default_value' => '',
				'label'         => esc_html__( 'Line Height', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		$second_level_row2 = overworld_edge_add_admin_row(
			array(
				'parent' => $second_level_group,
				'name'   => 'second_level_row2',
				'next'   => true
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_subtitle_font_style',
				'default_value' => '',
				'label'         => esc_html__( 'Font Style', 'overworld' ),
				'options'       => overworld_edge_get_font_style_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_subtitle_font_weight',
				'default_value' => '',
				'label'         => esc_html__( 'Font Weight', 'overworld' ),
				'options'       => overworld_edge_get_font_weight_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row2,
				'type'          => 'textsimple',
				'name'          => '404_subtitle_letter_spacing',
				'default_value' => '',
				'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $second_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_subtitle_text_transform',
				'default_value' => '',
				'label'         => esc_html__( 'Text Transform', 'overworld' ),
				'options'       => overworld_edge_get_text_transform_array()
			)
		);

        $second_level_group_responsive = overworld_edge_add_admin_group(
            array(
                'parent'      => $panel_404_options,
                'name'        => 'second_level_group_responsive',
                'title'       => esc_html__( 'Subtitle Style Responsive', 'overworld' ),
                'description' => esc_html__( 'Define responsive styles for 404 page subtitle (under 680px)', 'overworld' )
            )
        );

        $second_level_row3 = overworld_edge_add_admin_row(
            array(
                'parent' => $second_level_group_responsive,
                'name'   => 'second_level_row3'
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $second_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_subtitle_responsive_font_size',
                'default_value' => '',
                'label'         => esc_html__( 'Font Size', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $second_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_subtitle_responsive_line_height',
                'default_value' => '',
                'label'         => esc_html__( 'Line Height', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $second_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_subtitle_responsive_letter_spacing',
                'default_value' => '',
                'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_404_options,
				'type'          => 'text',
				'name'          => '404_text',
				'default_value' => '',
				'label'         => esc_html__( 'Text', 'overworld' ),
				'description'   => esc_html__( 'Enter text for 404 page.', 'overworld' )
			)
		);
		
		$third_level_group = overworld_edge_add_admin_group(
			array(
				'parent'      => $panel_404_options,
				'name'        => '$third_level_group',
				'title'       => esc_html__( 'Text Style', 'overworld' ),
				'description' => esc_html__( 'Define styles for 404 page text', 'overworld' )
			)
		);
		
		$third_level_row1 = overworld_edge_add_admin_row(
			array(
				'parent' => $third_level_group,
				'name'   => '$third_level_row1'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent' => $third_level_row1,
				'type'   => 'colorsimple',
				'name'   => '404_text_color',
				'label'  => esc_html__( 'Text Color', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row1,
				'type'          => 'fontsimple',
				'name'          => '404_text_google_fonts',
				'default_value' => '-1',
				'label'         => esc_html__( 'Font Family', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row1,
				'type'          => 'textsimple',
				'name'          => '404_text_font_size',
				'default_value' => '',
				'label'         => esc_html__( 'Font Size', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row1,
				'type'          => 'textsimple',
				'name'          => '404_text_line_height',
				'default_value' => '',
				'label'         => esc_html__( 'Line Height', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		$third_level_row2 = overworld_edge_add_admin_row(
			array(
				'parent' => $third_level_group,
				'name'   => '$third_level_row2',
				'next'   => true
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_text_font_style',
				'default_value' => '',
				'label'         => esc_html__( 'Font Style', 'overworld' ),
				'options'       => overworld_edge_get_font_style_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_text_font_weight',
				'default_value' => '',
				'label'         => esc_html__( 'Font Weight', 'overworld' ),
				'options'       => overworld_edge_get_font_weight_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row2,
				'type'          => 'textsimple',
				'name'          => '404_text_letter_spacing',
				'default_value' => '',
				'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $third_level_row2,
				'type'          => 'selectblanksimple',
				'name'          => '404_text_text_transform',
				'default_value' => '',
				'label'         => esc_html__( 'Text Transform', 'overworld' ),
				'options'       => overworld_edge_get_text_transform_array()
			)
		);

        $third_level_group_responsive = overworld_edge_add_admin_group(
            array(
                'parent'      => $panel_404_options,
                'name'        => 'third_level_group_responsive',
                'title'       => esc_html__( 'Text Style Responsive', 'overworld' ),
                'description' => esc_html__( 'Define responsive styles for 404 page text (under 680px)', 'overworld' )
            )
        );

        $third_level_row3 = overworld_edge_add_admin_row(
            array(
                'parent' => $third_level_group_responsive,
                'name'   => 'third_level_row3'
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $third_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_text_responsive_font_size',
                'default_value' => '',
                'label'         => esc_html__( 'Font Size', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $third_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_text_responsive_line_height',
                'default_value' => '',
                'label'         => esc_html__( 'Line Height', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $third_level_row3,
                'type'          => 'textsimple',
                'name'          => '404_text_responsive_letter_spacing',
                'default_value' => '',
                'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
                'args'          => array(
                    'suffix' => 'px'
                )
            )
        );
		
		overworld_edge_add_admin_field(
			array(
				'parent'      => $panel_404_options,
				'type'        => 'text',
				'name'        => '404_back_to_home',
				'label'       => esc_html__( 'Back to Home Button Label', 'overworld' ),
				'description' => esc_html__( 'Enter label for "Back to home" button', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_404_options,
				'type'          => 'select',
				'name'          => '404_button_style',
				'default_value' => '',
				'label'         => esc_html__( 'Button Skin', 'overworld' ),
				'description'   => esc_html__( 'Choose a style to make Back to Home button in that predefined style', 'overworld' ),
				'options'       => array(
					''            => esc_html__( 'Default', 'overworld' ),
					'light-style' => esc_html__( 'Light', 'overworld' )
				)
			)
		);
	}
	
	add_action( 'overworld_edge_action_options_map', 'overworld_edge_error_404_options_map', overworld_edge_set_options_map_position( '404' ) );
}