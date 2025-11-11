<?php

if ( ! function_exists( 'overworld_edge_get_title_types_meta_boxes' ) ) {
	function overworld_edge_get_title_types_meta_boxes() {
		$title_type_options = apply_filters( 'overworld_edge_filter_title_type_meta_boxes', $title_type_options = array( '' => esc_html__( 'Default', 'overworld' ) ) );
		
		return $title_type_options;
	}
}

foreach ( glob( OVERWORLD_EDGE_FRAMEWORK_MODULES_ROOT_DIR . '/title/types/*/admin/meta-boxes/*.php' ) as $meta_box_load ) {
	include_once $meta_box_load;
}

if ( ! function_exists( 'overworld_edge_map_title_meta' ) ) {
	function overworld_edge_map_title_meta() {
		$title_type_meta_boxes = overworld_edge_get_title_types_meta_boxes();
		
		$title_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => apply_filters( 'overworld_edge_filter_set_scope_for_meta_boxes', array( 'page', 'post' ), 'title_meta' ),
				'title' => esc_html__( 'Title', 'overworld' ),
				'name'  => 'title_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_show_title_area_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Show Title Area', 'overworld' ),
				'description'   => esc_html__( 'Disabling this option will turn off page title area', 'overworld' ),
				'parent'        => $title_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
			$show_title_area_meta_container = overworld_edge_add_admin_container(
				array(
					'parent'          => $title_meta_box,
					'name'            => 'edgtf_show_title_area_meta_container',
					'dependency' => array(
						'hide' => array(
							'edgtf_show_title_area_meta' => 'no'
						)
					)
				)
			);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_type_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Title Area Type', 'overworld' ),
						'description'   => esc_html__( 'Choose title type', 'overworld' ),
						'parent'        => $show_title_area_meta_container,
						'options'       => $title_type_meta_boxes
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_in_grid_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Title Area In Grid', 'overworld' ),
						'description'   => esc_html__( 'Set title area content to be in grid', 'overworld' ),
						'options'       => overworld_edge_get_yes_no_select_array(),
						'parent'        => $show_title_area_meta_container
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_title_area_height_meta',
						'type'        => 'text',
						'label'       => esc_html__( 'Height', 'overworld' ),
						'description' => esc_html__( 'Set a height for Title Area', 'overworld' ),
						'parent'      => $show_title_area_meta_container,
						'args'        => array(
							'col_width' => 2,
							'suffix'    => 'px'
						)
					)
				);

				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_title_area_height_mobile_meta',
						'type'        => 'text',
						'label'       => esc_html__( 'Height on Mobile', 'overworld' ),
						'description' => esc_html__( 'Set a height for Title Area on Mobile', 'overworld' ),
						'parent'      => $show_title_area_meta_container,
						'args'        => array(
							'col_width' => 2,
							'suffix'    => 'px'
						)
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_title_area_background_color_meta',
						'type'        => 'color',
						'label'       => esc_html__( 'Background Color', 'overworld' ),
						'description' => esc_html__( 'Choose a background color for title area', 'overworld' ),
						'parent'      => $show_title_area_meta_container
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_title_area_background_image_meta',
						'type'        => 'image',
						'label'       => esc_html__( 'Background Image', 'overworld' ),
						'description' => esc_html__( 'Choose an Image for title area', 'overworld' ),
						'parent'      => $show_title_area_meta_container
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_background_image_behavior_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Background Image Behavior', 'overworld' ),
						'description'   => esc_html__( 'Choose title area background image behavior', 'overworld' ),
						'parent'        => $show_title_area_meta_container,
						'options'       => array(
							''                    => esc_html__( 'Default', 'overworld' ),
							'hide'                => esc_html__( 'Hide Image', 'overworld' ),
							'responsive'          => esc_html__( 'Enable Responsive Image', 'overworld' ),
							'responsive-disabled' => esc_html__( 'Disable Responsive Image', 'overworld' ),
							'parallax'            => esc_html__( 'Enable Parallax Image', 'overworld' ),
							'parallax-zoom-out'   => esc_html__( 'Enable Parallax With Zoom Out Image', 'overworld' ),
							'parallax-disabled'   => esc_html__( 'Disable Parallax Image', 'overworld' )
						)
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_vertical_alignment_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Vertical Alignment', 'overworld' ),
						'description'   => esc_html__( 'Specify title area content vertical alignment', 'overworld' ),
						'parent'        => $show_title_area_meta_container,
						'options'       => array(
							''              => esc_html__( 'Default', 'overworld' ),
							'header-bottom' => esc_html__( 'From Bottom of Header', 'overworld' ),
							'window-top'    => esc_html__( 'From Window Top', 'overworld' )
						)
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_title_tag_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Title Tag', 'overworld' ),
						'options'       => overworld_edge_get_title_tag( true ),
						'parent'        => $show_title_area_meta_container
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_title_text_color_meta',
						'type'        => 'color',
						'label'       => esc_html__( 'Title Color', 'overworld' ),
						'description' => esc_html__( 'Choose a color for title text', 'overworld' ),
						'parent'      => $show_title_area_meta_container
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_subtitle_meta',
						'type'          => 'text',
						'default_value' => '',
						'label'         => esc_html__( 'Subtitle Text', 'overworld' ),
						'description'   => esc_html__( 'Enter your subtitle text', 'overworld' ),
						'parent'        => $show_title_area_meta_container,
						'args'          => array(
							'col_width' => 6
						)
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_area_subtitle_tag_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Subtitle Tag', 'overworld' ),
						'options'       => overworld_edge_get_title_tag( true, array( 'p' => 'p' ) ),
						'parent'        => $show_title_area_meta_container
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_subtitle_color_meta',
						'type'        => 'color',
						'label'       => esc_html__( 'Subtitle Color', 'overworld' ),
						'description' => esc_html__( 'Choose a color for subtitle text', 'overworld' ),
						'parent'      => $show_title_area_meta_container
					)
				);

			$show_title_separator_meta_container = overworld_edge_add_admin_container(
				array(
					'parent'          => $show_title_area_meta_container,
					'name'            => 'edgtf_show_title_separator_meta_container',
					'dependency' => array(
						'show' => array(
							'edgtf_title_area_type_meta' => array( 'standard-with-breadcrumbs', 'standard' )
						)
					)
				)
			);

				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_title_separator_meta',
						'type'          => 'select',
						'default_value' => '',
						'options'       => overworld_edge_get_yes_no_select_array(),
						'label'         => esc_html__( 'Title Left Separator', 'overworld' ),
						'description'   => esc_html__( 'Enable separator on the left side of title', 'overworld' ),
						'parent'        => $show_title_separator_meta_container
					)
				);

				$show_title_separator_style_meta_container = overworld_edge_add_admin_container(
					array(
						'parent'          => $show_title_separator_meta_container,
						'name'            => 'edgtf_show_title_separator_style_meta_container',
						'dependency' => array(
							'show' => array(
								'edgtf_title_separator_meta' => array( 'yes' )
							)
						)
					)
				);

					overworld_edge_create_meta_box_field(
						array(
							'name'        => 'edgtf_title_separator_background_color_meta',
							'type'        => 'color',
							'label'       => esc_html__( 'Background Color', 'overworld' ),
							'description' => esc_html__( 'Choose a background color for Title Separator', 'overworld' ),
							'parent'      => $show_title_separator_style_meta_container
						)
					);
		
		/***************** Additional Title Area Layout - start *****************/
		
		do_action( 'overworld_edge_action_additional_title_area_meta_boxes', $show_title_area_meta_container );
		
		/***************** Additional Title Area Layout - end *****************/
		
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_title_meta', 60 );
}