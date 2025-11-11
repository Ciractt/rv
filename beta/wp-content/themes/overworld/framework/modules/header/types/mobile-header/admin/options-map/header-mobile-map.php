<?php

if ( ! function_exists( 'overworld_edge_mobile_header_options_map' ) ) {
	function overworld_edge_mobile_header_options_map() {
		
		overworld_edge_add_admin_page(
			array(
				'slug'  => '_mobile_header',
				'title' => esc_html__( 'Mobile Header', 'overworld' ),
				'icon'  => 'fa fa-mobile'
			)
		);
		
		$panel_mobile_header = overworld_edge_add_admin_panel(
			array(
				'title' => esc_html__( 'Mobile Header', 'overworld' ),
				'name'  => 'panel_mobile_header',
				'page'  => '_mobile_header'
			)
		);
		
		$mobile_header_group = overworld_edge_add_admin_group(
			array(
				'parent' => $panel_mobile_header,
				'name'   => 'mobile_header_group',
				'title'  => esc_html__( 'Mobile Header Styles', 'overworld' )
			)
		);
		
		$mobile_header_row1 = overworld_edge_add_admin_row(
			array(
				'parent' => $mobile_header_group,
				'name'   => 'mobile_header_row1'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_header_height',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'Height', 'overworld' ),
				'parent' => $mobile_header_row1,
				'args'   => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_header_background_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Background Color', 'overworld' ),
				'parent' => $mobile_header_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_header_border_bottom_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Border Bottom Color', 'overworld' ),
				'parent' => $mobile_header_row1
			)
		);
		
		$mobile_menu_group = overworld_edge_add_admin_group(
			array(
				'parent' => $panel_mobile_header,
				'name'   => 'mobile_menu_group',
				'title'  => esc_html__( 'Mobile Menu Styles', 'overworld' )
			)
		);
		
		$mobile_menu_row1 = overworld_edge_add_admin_row(
			array(
				'parent' => $mobile_menu_group,
				'name'   => 'mobile_menu_row1'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_menu_background_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Background Color', 'overworld' ),
				'parent' => $mobile_menu_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_menu_border_bottom_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Border Bottom Color', 'overworld' ),
				'parent' => $mobile_menu_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_menu_separator_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Menu Item Separator Color', 'overworld' ),
				'parent' => $mobile_menu_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'        => 'mobile_logo_height',
				'type'        => 'text',
				'label'       => esc_html__( 'Logo Height For Mobile Header', 'overworld' ),
				'description' => esc_html__( 'Define logo height for screen size smaller than 1024px', 'overworld' ),
				'parent'      => $panel_mobile_header,
				'args'        => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'        => 'mobile_logo_height_phones',
				'type'        => 'text',
				'label'       => esc_html__( 'Logo Height For Mobile Devices', 'overworld' ),
				'description' => esc_html__( 'Define logo height for screen size smaller than 480px', 'overworld' ),
				'parent'      => $panel_mobile_header,
				'args'        => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'          => 'mobile_header_in_grid',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Mobile Header in Grid', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will put mobile header in grid', 'overworld' ),
				'parent'        => $panel_mobile_header,
				'args'          => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);

		$mobile_header_without_grid_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $panel_mobile_header,
				'name'            => 'mobile_header_without_grid_container',
				'dependency' => array(
					'show' => array(
						'mobile_header_in_grid' => 'no'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'name'        => 'mobile_header_without_grid_padding',
				'type'        => 'text',
				'label'       => esc_html__( 'Mobile Header Padding', 'overworld' ),
				'description' => esc_html__( 'Set padding for Mobile Header', 'overworld' ),
				'parent'      => $mobile_header_without_grid_container,
				'args'        => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_mobile_header,
				'name'   => 'mobile_header_fonts_title',
				'title'  => esc_html__( 'Typography', 'overworld' )
			)
		);
		
		$first_level_group = overworld_edge_add_admin_group(
			array(
				'parent'      => $panel_mobile_header,
				'name'        => 'first_level_group',
				'title'       => esc_html__( '1st Level Menu', 'overworld' ),
				'description' => esc_html__( 'Define styles for 1st level in Mobile Menu Navigation', 'overworld' )
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
				'name'   => 'mobile_text_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Text Color', 'overworld' ),
				'parent' => $first_level_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_text_hover_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Hover/Active Text Color', 'overworld' ),
				'parent' => $first_level_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_text_google_fonts',
				'type'   => 'fontsimple',
				'label'  => esc_html__( 'Font Family', 'overworld' ),
				'parent' => $first_level_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_text_font_size',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'Font Size', 'overworld' ),
				'parent' => $first_level_row1,
				'args'   => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		$first_level_row2 = overworld_edge_add_admin_row(
			array(
				'parent' => $first_level_group,
				'name'   => 'first_level_row2'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_text_line_height',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'Line Height', 'overworld' ),
				'parent' => $first_level_row2,
				'args'   => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'    => 'mobile_text_text_transform',
				'type'    => 'selectsimple',
				'label'   => esc_html__( 'Text Transform', 'overworld' ),
				'parent'  => $first_level_row2,
				'options' => overworld_edge_get_text_transform_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'    => 'mobile_text_font_style',
				'type'    => 'selectsimple',
				'label'   => esc_html__( 'Font Style', 'overworld' ),
				'parent'  => $first_level_row2,
				'options' => overworld_edge_get_font_style_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'    => 'mobile_text_font_weight',
				'type'    => 'selectsimple',
				'label'   => esc_html__( 'Font Weight', 'overworld' ),
				'parent'  => $first_level_row2,
				'options' => overworld_edge_get_font_weight_array()
			)
		);
		
		$first_level_row3 = overworld_edge_add_admin_row(
			array(
				'parent' => $first_level_group,
				'name'   => 'first_level_row3'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'textsimple',
				'name'          => 'mobile_text_letter_spacing',
				'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
				'default_value' => '',
				'parent'        => $first_level_row3,
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		$second_level_group = overworld_edge_add_admin_group(
			array(
				'parent'      => $panel_mobile_header,
				'name'        => 'second_level_group',
				'title'       => esc_html__( 'Dropdown Menu', 'overworld' ),
				'description' => esc_html__( 'Define styles for drop down menu items in Mobile Menu Navigation', 'overworld' )
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
				'name'   => 'mobile_dropdown_text_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Text Color', 'overworld' ),
				'parent' => $second_level_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_dropdown_text_hover_color',
				'type'   => 'colorsimple',
				'label'  => esc_html__( 'Hover/Active Text Color', 'overworld' ),
				'parent' => $second_level_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_dropdown_text_google_fonts',
				'type'   => 'fontsimple',
				'label'  => esc_html__( 'Font Family', 'overworld' ),
				'parent' => $second_level_row1
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_dropdown_text_font_size',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'Font Size', 'overworld' ),
				'parent' => $second_level_row1,
				'args'   => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		$second_level_row2 = overworld_edge_add_admin_row(
			array(
				'parent' => $second_level_group,
				'name'   => 'second_level_row2'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_dropdown_text_line_height',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'Line Height', 'overworld' ),
				'parent' => $second_level_row2,
				'args'   => array(
					'col_width' => 3,
					'suffix'    => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'    => 'mobile_dropdown_text_text_transform',
				'type'    => 'selectsimple',
				'label'   => esc_html__( 'Text Transform', 'overworld' ),
				'parent'  => $second_level_row2,
				'options' => overworld_edge_get_text_transform_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'    => 'mobile_dropdown_text_font_style',
				'type'    => 'selectsimple',
				'label'   => esc_html__( 'Font Style', 'overworld' ),
				'parent'  => $second_level_row2,
				'options' => overworld_edge_get_font_style_array()
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'    => 'mobile_dropdown_text_font_weight',
				'type'    => 'selectsimple',
				'label'   => esc_html__( 'Font Weight', 'overworld' ),
				'parent'  => $second_level_row2,
				'options' => overworld_edge_get_font_weight_array()
			)
		);
		
		$second_level_row3 = overworld_edge_add_admin_row(
			array(
				'parent' => $second_level_group,
				'name'   => 'second_level_row3'
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'textsimple',
				'name'          => 'mobile_dropdown_text_letter_spacing',
				'label'         => esc_html__( 'Letter Spacing', 'overworld' ),
				'default_value' => '',
				'parent'        => $second_level_row3,
				'args'          => array(
					'suffix' => 'px'
				)
			)
		);
		
		overworld_edge_add_admin_section_title(
			array(
				'name'   => 'mobile_opener_panel',
				'parent' => $panel_mobile_header,
				'title'  => esc_html__( 'Mobile Menu Opener', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'        => 'mobile_menu_title',
				'type'        => 'text',
				'label'       => esc_html__( 'Mobile Navigation Title', 'overworld' ),
				'description' => esc_html__( 'Enter title for mobile menu navigation', 'overworld' ),
				'parent'      => $panel_mobile_header,
				'args'        => array(
					'col_width' => 3
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_mobile_header,
				'type'          => 'select',
				'name'          => 'mobile_icon_source',
				'default_value' => 'svg_path',
				'label'         => esc_html__( 'Select Mobile Navigation Icon Source', 'overworld' ),
				'description'   => esc_html__( 'Choose whether you would like to use icons from an icon pack or SVG icons', 'overworld' ),
				'options'       => overworld_edge_get_icon_sources_array()
			)
		);

		$mobile_icon_pack_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $panel_mobile_header,
				'name'            => 'mobile_icon_pack_container',
				'dependency' => array(
					'show' => array(
						'mobile_icon_source' => 'icon_pack'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $mobile_icon_pack_container,
				'type'          => 'select',
				'name'          => 'mobile_icon_pack',
				'default_value' => 'font_elegant',
				'label'         => esc_html__( 'Mobile Navigation Icon Pack', 'overworld' ),
				'description'   => esc_html__( 'Choose icon pack for mobile navigation icon', 'overworld' ),
				'options'       => overworld_edge_icon_collections()->getIconCollectionsExclude( array( 'linea_icons', 'dripicons', 'simple_line_icons' ) )
			)
		);

		$mobile_icon_svg_path_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $panel_mobile_header,
				'name'            => 'mobile_icon_svg_path_container',
				'dependency' => array(
					'show' => array(
						'mobile_icon_source' => 'svg_path'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $mobile_icon_svg_path_container,
				'type'          => 'textarea',
				'name'          => 'mobile_icon_svg_path',
				'default_value' => overworld_edge_opener_svg(),
				'label'         => esc_html__( 'Mobile Navigation Icon SVG Path', 'overworld' ),
				'description'   => esc_html__( 'Enter your mobile navigation icon SVG path here. Please remove version and id attributes from your SVG path because of HTML validation', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_icon_color',
				'type'   => 'color',
				'label'  => esc_html__( 'Mobile Navigation Icon Color', 'overworld' ),
				'parent' => $panel_mobile_header
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'   => 'mobile_icon_hover_color',
				'type'   => 'color',
				'label'  => esc_html__( 'Mobile Navigation Icon Hover Color', 'overworld' ),
				'parent' => $panel_mobile_header
			)
		);
	}
	
	add_action( 'overworld_edge_action_options_map', 'overworld_edge_mobile_header_options_map', overworld_edge_set_options_map_position( 'mobile-header' ) );
}