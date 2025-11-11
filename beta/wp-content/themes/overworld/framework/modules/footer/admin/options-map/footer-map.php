<?php

if ( ! function_exists( 'overworld_edge_footer_options_map' ) ) {
	function overworld_edge_footer_options_map() {

		overworld_edge_add_admin_page(
			array(
				'slug'  => '_footer_page',
				'title' => esc_html__( 'Footer', 'overworld' ),
				'icon'  => 'fa fa-sort-amount-asc'
			)
		);

		$footer_panel = overworld_edge_add_admin_panel(
			array(
				'title' => esc_html__( 'Footer', 'overworld' ),
				'name'  => 'footer',
				'page'  => '_footer_page'
			)
		);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'footer_in_grid',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Footer in Grid', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will place Footer content in grid', 'overworld' ),
				'parent'        => $footer_panel
			)
		);

        overworld_edge_add_admin_field(
            array(
                'type'          => 'yesno',
                'name'          => 'uncovering_footer',
                'default_value' => 'no',
                'label'         => esc_html__( 'Uncovering Footer', 'overworld' ),
                'description'   => esc_html__( 'Enabling this option will make Footer gradually appear on scroll', 'overworld' ),
                'parent'        => $footer_panel
            )
        );

		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'show_footer_top',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show Footer Top', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will show Footer Top area', 'overworld' ),
				'parent'        => $footer_panel
			)
		);
		
		$show_footer_top_container = overworld_edge_add_admin_container(
			array(
				'name'       => 'show_footer_top_container',
				'parent'     => $footer_panel,
				'dependency' => array(
					'show' => array(
						'show_footer_top' => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'select',
				'name'          => 'footer_top_columns',
				'parent'        => $show_footer_top_container,
				'default_value' => '3 3 3 3',
				'label'         => esc_html__( 'Footer Top Columns', 'overworld' ),
				'description'   => esc_html__( 'Choose number of columns for Footer Top area', 'overworld' ),
				'options'       => array(
					'12' => '1',
					'6 6' => '2',
					'4 4 4' => '3',
                    '3 6 3' => '3 (25% + 50% + 25%)',
					'3 3 3 3' => '4'
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'select',
				'name'          => 'footer_top_columns_alignment',
				'default_value' => 'left',
				'label'         => esc_html__( 'Footer Top Columns Alignment', 'overworld' ),
				'description'   => esc_html__( 'Text Alignment in Footer Columns', 'overworld' ),
				'options'       => array(
					''       => esc_html__( 'Default', 'overworld' ),
					'left'   => esc_html__( 'Left', 'overworld' ),
					'center' => esc_html__( 'Center', 'overworld' ),
					'right'  => esc_html__( 'Right', 'overworld' )
				),
				'parent'        => $show_footer_top_container
			)
		);
		
		$footer_top_styles_group = overworld_edge_add_admin_group(
			array(
				'name'        => 'footer_top_styles_group',
				'title'       => esc_html__( 'Footer Top Styles', 'overworld' ),
				'description' => esc_html__( 'Define style for footer top area', 'overworld' ),
				'parent'      => $show_footer_top_container
			)
		);
		
		$footer_top_styles_row_1 = overworld_edge_add_admin_row(
			array(
				'name'   => 'footer_top_styles_row_1',
				'parent' => $footer_top_styles_group
			)
		);
		
			overworld_edge_add_admin_field(
				array(
					'name'   => 'footer_top_background_color',
					'type'   => 'colorsimple',
					'label'  => esc_html__( 'Background Color', 'overworld' ),
					'parent' => $footer_top_styles_row_1
				)
			);
			
			overworld_edge_add_admin_field(
				array(
					'name'   => 'footer_top_border_color',
					'type'   => 'colorsimple',
					'label'  => esc_html__( 'Border Color', 'overworld' ),
					'parent' => $footer_top_styles_row_1
				)
			);
			
			overworld_edge_add_admin_field(
				array(
					'name'   => 'footer_top_border_width',
					'type'   => 'textsimple',
					'label'  => esc_html__( 'Border Width', 'overworld' ),
					'parent' => $footer_top_styles_row_1,
					'args'   => array(
						'suffix' => 'px'
					)
				)
			);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'show_footer_bottom',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show Footer Bottom', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will show Footer Bottom area', 'overworld' ),
				'parent'        => $footer_panel
			)
		);

		$show_footer_bottom_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'show_footer_bottom_container',
				'parent'          => $footer_panel,
				'dependency' => array(
					'show' => array(
						'show_footer_bottom'  => 'yes'
					)
				)
			)
		);

		overworld_edge_add_admin_field(
			array(
				'type'          => 'select',
				'name'          => 'footer_bottom_columns',
				'default_value' => '6 6',
				'label'         => esc_html__( 'Footer Bottom Columns', 'overworld' ),
				'description'   => esc_html__( 'Choose number of columns for Footer Bottom area', 'overworld' ),
				'options'       => array(
					'12' => '1',
					'6 6' => '2',
					'4 4 4' => '3'
				),
				'parent'        => $show_footer_bottom_container
			)
		);
		
		$footer_bottom_styles_group = overworld_edge_add_admin_group(
			array(
				'name'        => 'footer_bottom_styles_group',
				'title'       => esc_html__( 'Footer Bottom Styles', 'overworld' ),
				'description' => esc_html__( 'Define style for footer bottom area', 'overworld' ),
				'parent'      => $show_footer_bottom_container
			)
		);
		
		$footer_bottom_styles_row_1 = overworld_edge_add_admin_row(
			array(
				'name'   => 'footer_bottom_styles_row_1',
				'parent' => $footer_bottom_styles_group
			)
		);
		
			overworld_edge_add_admin_field(
				array(
					'name'   => 'footer_bottom_background_color',
					'type'   => 'colorsimple',
					'label'  => esc_html__( 'Background Color', 'overworld' ),
					'parent' => $footer_bottom_styles_row_1
				)
			);
			
			overworld_edge_add_admin_field(
				array(
					'name'   => 'footer_bottom_border_color',
					'type'   => 'colorsimple',
					'label'  => esc_html__( 'Border Color', 'overworld' ),
					'parent' => $footer_bottom_styles_row_1
				)
			);
			
			overworld_edge_add_admin_field(
				array(
					'name'   => 'footer_bottom_border_width',
					'type'   => 'textsimple',
					'label'  => esc_html__( 'Border Width', 'overworld' ),
					'parent' => $footer_bottom_styles_row_1,
					'args'   => array(
						'suffix' => 'px'
					)
				)
			);
	}

	add_action( 'overworld_edge_action_options_map', 'overworld_edge_footer_options_map', overworld_edge_set_options_map_position( 'footer' ) );
}