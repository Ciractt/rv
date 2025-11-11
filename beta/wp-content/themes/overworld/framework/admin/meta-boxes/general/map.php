<?php

if ( ! function_exists( 'overworld_edge_map_general_meta' ) ) {
	function overworld_edge_map_general_meta() {
		
		$general_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => apply_filters( 'overworld_edge_filter_set_scope_for_meta_boxes', array( 'page', 'post' ), 'general_meta' ),
				'title' => esc_html__( 'General', 'overworld' ),
				'name'  => 'general_meta'
			)
		);
		
		/***************** Slider Layout - begin **********************/
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_page_slider_meta',
				'type'        => 'text',
				'label'       => esc_html__( 'Slider Shortcode', 'overworld' ),
				'description' => esc_html__( 'Paste your slider shortcode here', 'overworld' ),
				'parent'      => $general_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'   => 'edgtf_page_slider_top_offset_meta',
				'type'   => 'text',
				'label'  => esc_html__( 'Slider Shortcode Top Offset', 'overworld' ),
				'parent' => $general_meta_box,
				'args'   => array(
					'col_width' => 2,
					'suffix'    => esc_html__( 'px or %', 'overworld' )
				)
			)
		);
		
		/***************** Slider Layout - begin **********************/
		
		/***************** Content Layout - begin **********************/
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_page_content_behind_header_meta',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Always put content behind header', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will put page content behind page header', 'overworld' ),
				'parent'        => $general_meta_box
			)
		);
		
		$edgtf_content_padding_group = overworld_edge_add_admin_group(
			array(
				'name'        => 'content_padding_group',
				'title'       => esc_html__( 'Content Styles', 'overworld' ),
				'description' => esc_html__( 'Define styles for Content area', 'overworld' ),
				'parent'      => $general_meta_box
			)
		);
		
			$edgtf_content_padding_row = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_content_padding_row',
					'parent' => $edgtf_content_padding_group
				)
			);
			
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_page_background_color_meta',
						'type'        => 'colorsimple',
						'label'       => esc_html__( 'Page Background Color', 'overworld' ),
						'parent'      => $edgtf_content_padding_row
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_page_background_image_meta',
						'type'          => 'imagesimple',
						'label'         => esc_html__( 'Page Background Image', 'overworld' ),
						'parent'        => $edgtf_content_padding_row
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_page_background_repeat_meta',
						'type'          => 'selectsimple',
						'default_value' => '',
						'label'         => esc_html__( 'Page Background Image Repeat', 'overworld' ),
						'options'       => overworld_edge_get_yes_no_select_array(),
						'parent'        => $edgtf_content_padding_row
					)
				);
		
			$edgtf_content_padding_row_1 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_content_padding_row_1',
					'next'   => true,
					'parent' => $edgtf_content_padding_group
				)
			);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'   => 'edgtf_page_content_padding',
						'type'   => 'textsimple',
						'label'  => esc_html__( 'Content Padding (eg. 10px 5px 10px 5px)', 'overworld' ),
						'parent' => $edgtf_content_padding_row_1,
						'args'        => array(
							'col_width' => 4
						)
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'    => 'edgtf_page_content_padding_mobile',
						'type'    => 'textsimple',
						'label'   => esc_html__( 'Content Padding for mobile (eg. 10px 5px 10px 5px)', 'overworld' ),
						'parent'  => $edgtf_content_padding_row_1,
						'args'        => array(
							'col_width' => 4
						)
					)
				);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_initial_content_width_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Initial Width of Content', 'overworld' ),
				'description'   => esc_html__( 'Choose the initial width of content which is in grid (Applies to pages set to "Default Template" and rows set to "In Grid")', 'overworld' ),
				'parent'        => $general_meta_box,
				'options'       => array(
					''                => esc_html__( 'Default', 'overworld' ),
					'edgtf-grid-1300' => esc_html__( '1300px', 'overworld' ),
					'edgtf-grid-1200' => esc_html__( '1200px', 'overworld' ),
					'edgtf-grid-1100' => esc_html__( '1100px', 'overworld' ),
					'edgtf-grid-1000' => esc_html__( '1000px', 'overworld' ),
					'edgtf-grid-800'  => esc_html__( '800px', 'overworld' )
				)
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_page_grid_space_meta',
				'type'        => 'select',
				'default_value' => '',
				'label'       => esc_html__( 'Grid Layout Space', 'overworld' ),
				'description' => esc_html__( 'Choose a space between content layout and sidebar layout for your page', 'overworld' ),
				'options'     => overworld_edge_get_space_between_items_array( true ),
				'parent'      => $general_meta_box
			)
		);
		
		/***************** Content Layout - end **********************/
		
		/***************** Boxed Layout - begin **********************/
		
		overworld_edge_create_meta_box_field(
			array(
				'name'    => 'edgtf_boxed_meta',
				'type'    => 'select',
				'label'   => esc_html__( 'Boxed Layout', 'overworld' ),
				'parent'  => $general_meta_box,
				'options' => overworld_edge_get_yes_no_select_array()
			)
		);
		
			$boxed_container_meta = overworld_edge_add_admin_container(
				array(
					'parent'          => $general_meta_box,
					'name'            => 'boxed_container_meta',
					'dependency' => array(
						'hide' => array(
							'edgtf_boxed_meta' => array( '', 'no' )
						)
					)
				)
			);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_page_background_color_in_box_meta',
						'type'        => 'color',
						'label'       => esc_html__( 'Page Background Color', 'overworld' ),
						'description' => esc_html__( 'Choose the page background color outside box', 'overworld' ),
						'parent'      => $boxed_container_meta
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_boxed_background_image_meta',
						'type'        => 'image',
						'label'       => esc_html__( 'Background Image', 'overworld' ),
						'description' => esc_html__( 'Choose an image to be displayed in background', 'overworld' ),
						'parent'      => $boxed_container_meta
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_boxed_pattern_background_image_meta',
						'type'        => 'image',
						'label'       => esc_html__( 'Background Pattern', 'overworld' ),
						'description' => esc_html__( 'Choose an image to be used as background pattern', 'overworld' ),
						'parent'      => $boxed_container_meta
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'          => 'edgtf_boxed_background_image_attachment_meta',
						'type'          => 'select',
						'default_value' => '',
						'label'         => esc_html__( 'Background Image Attachment', 'overworld' ),
						'description'   => esc_html__( 'Choose background image attachment', 'overworld' ),
						'parent'        => $boxed_container_meta,
						'options'       => array(
							''       => esc_html__( 'Default', 'overworld' ),
							'fixed'  => esc_html__( 'Fixed', 'overworld' ),
							'scroll' => esc_html__( 'Scroll', 'overworld' )
						)
					)
				);
		
		/***************** Boxed Layout - end **********************/
		
		/***************** Passepartout Layout - begin **********************/
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_paspartu_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Passepartout', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will display passepartout around site content', 'overworld' ),
				'parent'        => $general_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array(),
			)
		);
		
			$paspartu_container_meta = overworld_edge_add_admin_container(
				array(
					'parent'          => $general_meta_box,
					'name'            => 'edgtf_paspartu_container_meta',
					'dependency' => array(
						'hide' => array(
							'edgtf_paspartu_meta'  => array('','no')
						)
					)
				)
			);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_paspartu_color_meta',
						'type'        => 'color',
						'label'       => esc_html__( 'Passepartout Color', 'overworld' ),
						'description' => esc_html__( 'Choose passepartout color, default value is #ffffff', 'overworld' ),
						'parent'      => $paspartu_container_meta
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_paspartu_width_meta',
						'type'        => 'text',
						'label'       => esc_html__( 'Passepartout Size', 'overworld' ),
						'description' => esc_html__( 'Enter size amount for passepartout', 'overworld' ),
						'parent'      => $paspartu_container_meta,
						'args'        => array(
							'col_width' => 2,
							'suffix'    => 'px or %'
						)
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_paspartu_responsive_width_meta',
						'type'        => 'text',
						'label'       => esc_html__( 'Responsive Passepartout Size', 'overworld' ),
						'description' => esc_html__( 'Enter size amount for passepartout for smaller screens (tablets and mobiles view)', 'overworld' ),
						'parent'      => $paspartu_container_meta,
						'args'        => array(
							'col_width' => 2,
							'suffix'    => 'px or %'
						)
					)
				);
				
				overworld_edge_create_meta_box_field(
					array(
						'parent'        => $paspartu_container_meta,
						'type'          => 'select',
						'default_value' => '',
						'name'          => 'edgtf_disable_top_paspartu_meta',
						'label'         => esc_html__( 'Disable Top Passepartout', 'overworld' ),
						'options'       => overworld_edge_get_yes_no_select_array(),
					)
				);
		
				overworld_edge_create_meta_box_field(
					array(
						'parent'        => $paspartu_container_meta,
						'type'          => 'select',
						'default_value' => '',
						'name'          => 'edgtf_enable_fixed_paspartu_meta',
						'label'         => esc_html__( 'Enable Fixed Passepartout', 'overworld' ),
						'description'   => esc_html__( 'Enabling this option will set fixed passepartout for your screens', 'overworld' ),
						'options'       => overworld_edge_get_yes_no_select_array(),
					)
				);
		
		/***************** Passepartout Layout - end **********************/
		
		/***************** Smooth Page Transitions Layout - begin **********************/
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_smooth_page_transitions_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Smooth Page Transitions', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will perform a smooth transition between pages when clicking on links', 'overworld' ),
				'parent'        => $general_meta_box,
				'options'       => overworld_edge_get_yes_no_select_array()
			)
		);
		
			$page_transitions_container_meta = overworld_edge_add_admin_container(
				array(
					'parent'     => $general_meta_box,
					'name'       => 'page_transitions_container_meta',
					'dependency' => array(
						'hide' => array(
							'edgtf_smooth_page_transitions_meta' => array( '', 'no' )
						)
					)
				)
			);
		
				overworld_edge_create_meta_box_field(
					array(
						'name'        => 'edgtf_page_transition_preloader_meta',
						'type'        => 'select',
						'label'       => esc_html__( 'Enable Preloading Animation', 'overworld' ),
						'description' => esc_html__( 'Enabling this option will display an animated preloader while the page content is loading', 'overworld' ),
						'parent'      => $page_transitions_container_meta,
						'options'     => overworld_edge_get_yes_no_select_array()
					)
				);
		
				$page_transition_preloader_container_meta = overworld_edge_add_admin_container(
					array(
						'parent'     => $page_transitions_container_meta,
						'name'       => 'page_transition_preloader_container_meta',
						'dependency' => array(
							'hide' => array(
								'edgtf_page_transition_preloader_meta' => array( '', 'no' )
							)
						)
					)
				);
				
					overworld_edge_create_meta_box_field(
						array(
							'name'   => 'edgtf_smooth_pt_bgnd_color_meta',
							'type'   => 'color',
							'label'  => esc_html__( 'Page Loader Background Color', 'overworld' ),
							'parent' => $page_transition_preloader_container_meta
						)
					);
					
					$group_pt_spinner_animation_meta = overworld_edge_add_admin_group(
						array(
							'name'        => 'group_pt_spinner_animation_meta',
							'title'       => esc_html__( 'Loader Style', 'overworld' ),
							'description' => esc_html__( 'Define styles for loader spinner animation', 'overworld' ),
							'parent'      => $page_transition_preloader_container_meta
						)
					);
					
					$row_pt_spinner_animation_meta = overworld_edge_add_admin_row(
						array(
							'name'   => 'row_pt_spinner_animation_meta',
							'parent' => $group_pt_spinner_animation_meta
						)
					);
					
					overworld_edge_create_meta_box_field(
						array(
							'type'    => 'selectsimple',
							'name'    => 'edgtf_smooth_pt_spinner_type_meta',
							'label'   => esc_html__( 'Spinner Type', 'overworld' ),
							'parent'  => $row_pt_spinner_animation_meta,
							'options' => array(
								''                      => esc_html__( 'Default', 'overworld' ),
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
					
					overworld_edge_create_meta_box_field(
						array(
							'type'   => 'colorsimple',
							'name'   => 'edgtf_smooth_pt_spinner_color_meta',
							'label'  => esc_html__( 'Spinner Color', 'overworld' ),
							'parent' => $row_pt_spinner_animation_meta
						)
					);

					overworld_edge_create_meta_box_field(
						array(
							'name'          => 'edgtf_pt_loading_logo_meta',
							'type'          => 'image',
							'label'         => esc_html__('Preloader Logo Image', 'overworld'),
							'description'   => esc_html__('Choose preloader logo image to be displayed until the page is loaded', 'overworld'),
							'parent'        => $row_pt_spinner_animation_meta,
							'dependency' => array(
								'show' => array(
									'edgtf_smooth_pt_spinner_type_meta' => 'overworld_spinner'
								)
							)
						)
					);
					
					overworld_edge_create_meta_box_field(
						array(
							'name'        => 'edgtf_page_transition_fadeout_meta',
							'type'        => 'select',
							'label'       => esc_html__( 'Enable Fade Out Animation', 'overworld' ),
							'description' => esc_html__( 'Enabling this option will turn on fade out animation when leaving page', 'overworld' ),
							'options'     => overworld_edge_get_yes_no_select_array(),
							'parent'      => $page_transitions_container_meta
						
						)
					);
		
		/***************** Smooth Page Transitions Layout - end **********************/
		
		/***************** Comments Layout - begin **********************/
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_page_comments_meta',
				'type'        => 'select',
				'label'       => esc_html__( 'Show Comments', 'overworld' ),
				'description' => esc_html__( 'Enabling this option will show comments on your page', 'overworld' ),
				'parent'      => $general_meta_box,
				'options'     => overworld_edge_get_yes_no_select_array()
			)
		);
		
		/***************** Comments Layout - end **********************/
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_general_meta', 10 );
}

if ( ! function_exists( 'overworld_edge_container_background_style' ) ) {
	/**
	 * Function that return container style
	 *
	 * @param $style
	 *
	 * @return string
	 */
	function overworld_edge_container_background_style( $style ) {
		$page_id      = overworld_edge_get_page_id();
		$class_prefix = overworld_edge_get_unique_page_class( $page_id, true );
		
		$container_selector = array(
			$class_prefix . ' .edgtf-content'
		);
		
		$container_class        = array();
		$current_style = '';
		$page_background_color  = get_post_meta( $page_id, 'edgtf_page_background_color_meta', true );
		$page_background_image  = get_post_meta( $page_id, 'edgtf_page_background_image_meta', true );
		$page_background_repeat = get_post_meta( $page_id, 'edgtf_page_background_repeat_meta', true );
		
		if ( ! empty( $page_background_color ) ) {
			$container_class['background-color'] = $page_background_color;
		}
		
		if ( ! empty( $page_background_image ) ) {
			$container_class['background-image'] = 'url(' . esc_url( $page_background_image ) . ')';
			
			if ( $page_background_repeat === 'yes' ) {
				$container_class['background-repeat']   = 'repeat';
				$container_class['background-position'] = '0 0';
			} else {
				$container_class['background-repeat']   = 'no-repeat';
				$container_class['background-position'] = 'center 0';
				$container_class['background-size']     = 'cover';
			}
		}

		if(! empty( $container_class )) {
			$current_style = overworld_edge_dynamic_css( $container_selector, $container_class );
		}

		$current_style = $current_style . $style;
		
		return $current_style;
	}
	
	add_filter( 'overworld_edge_filter_add_page_custom_style', 'overworld_edge_container_background_style' );
}