<?php

if ( ! function_exists( 'overworld_edge_logo_options_map' ) ) {
	function overworld_edge_logo_options_map() {
		
		overworld_edge_add_admin_page(
			array(
				'slug'  => '_logo_page',
				'title' => esc_html__( 'Logo', 'overworld' ),
				'icon'  => 'fa fa-coffee'
			)
		);
		
		$panel_logo = overworld_edge_add_admin_panel(
			array(
				'page'  => '_logo_page',
				'name'  => 'panel_logo',
				'title' => esc_html__( 'Logo', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'parent'        => $panel_logo,
				'type'          => 'yesno',
				'name'          => 'hide_logo',
				'default_value' => 'no',
				'label'         => esc_html__( 'Hide Logo', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will hide logo image', 'overworld' )
			)
		);
		
		$hide_logo_container = overworld_edge_add_admin_container(
			array(
				'parent'          => $panel_logo,
				'name'            => 'hide_logo_container',
				'dependency' => array(
					'hide' => array(
						'hide_logo'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'logo_image',
				'type'          => 'image',
				'default_value' => OVERWORLD_EDGE_ASSETS_ROOT . "/img/logo.png",
				'label'         => esc_html__( 'Logo Image - Default', 'overworld' ),
				'parent'        => $hide_logo_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'logo_image_dark',
				'type'          => 'image',
				'default_value' => OVERWORLD_EDGE_ASSETS_ROOT . "/img/logo.png",
				'label'         => esc_html__( 'Logo Image - Dark', 'overworld' ),
				'parent'        => $hide_logo_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'logo_image_light',
				'type'          => 'image',
				'default_value' => OVERWORLD_EDGE_ASSETS_ROOT . "/img/logo_white.png",
				'label'         => esc_html__( 'Logo Image - Light', 'overworld' ),
				'parent'        => $hide_logo_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'logo_image_sticky',
				'type'          => 'image',
				'default_value' => OVERWORLD_EDGE_ASSETS_ROOT . "/img/logo.png",
				'label'         => esc_html__( 'Logo Image - Sticky', 'overworld' ),
				'parent'        => $hide_logo_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'name'          => 'logo_image_mobile',
				'type'          => 'image',
				'default_value' => OVERWORLD_EDGE_ASSETS_ROOT . "/img/logo-mobile.png",
				'label'         => esc_html__( 'Logo Image - Mobile', 'overworld' ),
				'parent'        => $hide_logo_container
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $hide_logo_container,
				'type'          => 'yesno',
				'name'          => 'logo_enable_box_shadow',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable shadow around logo', 'overworld' ),
				'description'   => esc_html__( 'This option will enable shadow around logo', 'overworld' )
			)
		);

		overworld_edge_add_admin_field(
			array(
				'parent'        => $hide_logo_container,
				'type'          => 'yesno',
				'name'          => 'logo_allow_outside_header',
				'default_value' => 'no',
				'label'         => esc_html__( 'Logo outside header box', 'overworld' ),
				'description'   => esc_html__( 'This option will allow logo to be outside header', 'overworld' )
			)
		);
	}
	
	add_action( 'overworld_edge_action_options_map', 'overworld_edge_logo_options_map', overworld_edge_set_options_map_position( 'logo' ) );
}