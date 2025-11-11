<?php

if ( ! function_exists( 'overworld_edge_map_footer_meta' ) ) {
	function overworld_edge_map_footer_meta() {
		
		$footer_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => apply_filters( 'overworld_edge_filter_set_scope_for_meta_boxes', array( 'page', 'post' ), 'footer_meta' ),
				'title' => esc_html__( 'Footer', 'overworld' ),
				'name'  => 'footer_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_disable_footer_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Disable Footer For This Page', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will hide footer on this page', 'overworld' ),
				'options'       => overworld_edge_get_yes_no_select_array(),
				'parent'        => $footer_meta_box
			)
		);
		
		$show_footer_meta_container = overworld_edge_add_admin_container(
			array(
				'name'       => 'edgtf_show_footer_meta_container',
				'parent'     => $footer_meta_box,
				'dependency' => array(
					'hide' => array(
						'edgtf_disable_footer_meta' => 'yes'
					)
				)
			)
		);
		
			overworld_edge_create_meta_box_field(
				array(
					'name'          => 'edgtf_footer_in_grid_meta',
					'type'          => 'select',
					'default_value' => '',
					'label'         => esc_html__( 'Footer in Grid', 'overworld' ),
					'description'   => esc_html__( 'Enabling this option will place Footer content in grid', 'overworld' ),
					'options'       => overworld_edge_get_yes_no_select_array(),
					'parent'        => $show_footer_meta_container
				)
			);
			
			overworld_edge_create_meta_box_field(
				array(
					'name'          => 'edgtf_uncovering_footer_meta',
					'type'          => 'select',
					'default_value' => '',
					'label'         => esc_html__( 'Uncovering Footer', 'overworld' ),
					'description'   => esc_html__( 'Enabling this option will make Footer gradually appear on scroll', 'overworld' ),
					'options'       => overworld_edge_get_yes_no_select_array(),
					'parent'        => $show_footer_meta_container
				)
			);
		
			overworld_edge_create_meta_box_field(
				array(
					'name'          => 'edgtf_show_footer_top_meta',
					'type'          => 'select',
					'default_value' => '',
					'label'         => esc_html__( 'Show Footer Top', 'overworld' ),
					'description'   => esc_html__( 'Enabling this option will show Footer Top area', 'overworld' ),
					'options'       => overworld_edge_get_yes_no_select_array(),
					'parent'        => $show_footer_meta_container
				)
			);
		
			$footer_top_styles_group = overworld_edge_add_admin_group(
				array(
					'name'        => 'footer_top_styles_group',
					'title'       => esc_html__( 'Footer Top Styles', 'overworld' ),
					'description' => esc_html__( 'Define style for footer top area', 'overworld' ),
					'parent'      => $show_footer_meta_container,
					'dependency'  => array(
						'hide' => array(
							'edgtf_show_footer_top_meta' => 'no'
						)
					)
				)
			);
			
			$footer_top_styles_row_1 = overworld_edge_add_admin_row(
				array(
					'name'   => 'footer_top_styles_row_1',
					'parent' => $footer_top_styles_group
				)
			);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_footer_top_background_color_meta',
						'type'   => 'colorsimple',
						'label'  => esc_html__( 'Background Color', 'overworld' ),
						'parent' => $footer_top_styles_row_1
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_footer_top_border_color_meta',
						'type'   => 'colorsimple',
						'label'  => esc_html__( 'Border Color', 'overworld' ),
						'parent' => $footer_top_styles_row_1
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_footer_top_border_width_meta',
						'type'   => 'textsimple',
						'label'  => esc_html__( 'Border Width', 'overworld' ),
						'parent' => $footer_top_styles_row_1,
						'args'   => array(
							'suffix' => 'px'
						)
					)
				);
			
			overworld_edge_create_meta_box_field(
				array(
					'name'          => 'edgtf_show_footer_bottom_meta',
					'type'          => 'select',
					'default_value' => '',
					'label'         => esc_html__( 'Show Footer Bottom', 'overworld' ),
					'description'   => esc_html__( 'Enabling this option will show Footer Bottom area', 'overworld' ),
					'options'       => overworld_edge_get_yes_no_select_array(),
					'parent'        => $show_footer_meta_container
				)
			);
		
			$footer_bottom_styles_group = overworld_edge_add_admin_group(
				array(
					'name'        => 'footer_bottom_styles_group',
					'title'       => esc_html__( 'Footer Bottom Styles', 'overworld' ),
					'description' => esc_html__( 'Define style for footer bottom area', 'overworld' ),
					'parent'      => $show_footer_meta_container,
					'dependency'  => array(
						'hide' => array(
							'edgtf_show_footer_bottom_meta' => 'no'
						)
					)
				)
			);
			
			$footer_bottom_styles_row_1 = overworld_edge_add_admin_row(
				array(
					'name'   => 'footer_bottom_styles_row_1',
					'parent' => $footer_bottom_styles_group
				)
			);
			
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_footer_bottom_background_color_meta',
						'type'   => 'colorsimple',
						'label'  => esc_html__( 'Background Color', 'overworld' ),
						'parent' => $footer_bottom_styles_row_1
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_footer_bottom_border_color_meta',
						'type'   => 'colorsimple',
						'label'  => esc_html__( 'Border Color', 'overworld' ),
						'parent' => $footer_bottom_styles_row_1
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_footer_bottom_border_width_meta',
						'type'   => 'textsimple',
						'label'  => esc_html__( 'Border Width', 'overworld' ),
						'parent' => $footer_bottom_styles_row_1,
						'args'   => array(
							'suffix' => 'px'
						)
					)
				);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_footer_meta', 70 );
}