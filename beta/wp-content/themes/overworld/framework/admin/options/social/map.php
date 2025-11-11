<?php

if ( ! function_exists( 'overworld_edge_social_options_map' ) ) {
	function overworld_edge_social_options_map() {

	    $page = '_social_page';
		
		overworld_edge_add_admin_page(
			array(
				'slug'  => '_social_page',
				'title' => esc_html__( 'Social Networks', 'overworld' ),
				'icon'  => 'fa fa-share-alt'
			)
		);
		
		/**
		 * Enable Social Share
		 */
		$panel_social_share = overworld_edge_add_admin_panel(
			array(
				'page'  => '_social_page',
				'name'  => 'panel_social_share',
				'title' => esc_html__( 'Enable Social Share', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_social_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Social Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow social share on networks of your choice', 'overworld' ),
				'parent'        => $panel_social_share
			)
		);
		
		$panel_show_social_share_on = overworld_edge_add_admin_panel(
			array(
				'page'            => '_social_page',
				'name'            => 'panel_show_social_share_on',
				'title'           => esc_html__( 'Show Social Share On', 'overworld' ),
				'dependency' => array(
					'show' => array(
						'enable_social_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_social_share_on_post',
				'default_value' => 'no',
				'label'         => esc_html__( 'Posts', 'overworld' ),
				'description'   => esc_html__( 'Show Social Share on Blog Posts', 'overworld' ),
				'parent'        => $panel_show_social_share_on
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_social_share_on_page',
				'default_value' => 'no',
				'label'         => esc_html__( 'Pages', 'overworld' ),
				'description'   => esc_html__( 'Show Social Share on Pages', 'overworld' ),
				'parent'        => $panel_show_social_share_on
			)
		);

        /**
         * Action for embedding social share option for custom post types
         */
		do_action('overworld_edge_action_post_types_social_share', $panel_show_social_share_on);
		
		/**
		 * Social Share Networks
		 */
		$panel_social_networks = overworld_edge_add_admin_panel(
			array(
				'page'            => '_social_page',
				'name'            => 'panel_social_networks',
				'title'           => esc_html__( 'Social Networks', 'overworld' ),
				'dependency' => array(
					'hide' => array(
						'enable_social_share'  => 'no'
					)
				)
			)
		);
		
		/**
		 * Facebook
		 */
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_social_networks,
				'name'   => 'facebook_title',
				'title'  => esc_html__( 'Share on Facebook', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_facebook_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow sharing via Facebook', 'overworld' ),
				'parent'        => $panel_social_networks
			)
		);
		
		$enable_facebook_share_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_facebook_share_container',
				'parent'          => $panel_social_networks,
				'dependency' => array(
					'show' => array(
						'enable_facebook_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'facebook_icon',
				'default_value' => '',
				'label'         => esc_html__( 'Upload Icon', 'overworld' ),
				'parent'        => $enable_facebook_share_container
			)
		);
		
		/**
		 * Twitter
		 */
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_social_networks,
				'name'   => 'twitter_title',
				'title'  => esc_html__( 'Share on Twitter', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_twitter_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow sharing via Twitter', 'overworld' ),
				'parent'        => $panel_social_networks
			)
		);
		
		$enable_twitter_share_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_twitter_share_container',
				'parent'          => $panel_social_networks,
				'dependency' => array(
					'show' => array(
						'enable_twitter_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'twitter_icon',
				'default_value' => '',
				'label'         => esc_html__( 'Upload Icon', 'overworld' ),
				'parent'        => $enable_twitter_share_container
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'text',
				'name'          => 'twitter_via',
				'default_value' => '',
				'label'         => esc_html__( 'Via', 'overworld' ),
				'parent'        => $enable_twitter_share_container
			)
		);
		
		/**
		 * Linked In
		 */
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_social_networks,
				'name'   => 'linkedin_title',
				'title'  => esc_html__( 'Share on LinkedIn', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_linkedin_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow sharing via LinkedIn', 'overworld' ),
				'parent'        => $panel_social_networks
			)
		);
		
		$enable_linkedin_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_linkedin_container',
				'parent'          => $panel_social_networks,
				'dependency' => array(
					'show' => array(
						'enable_linkedin_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'linkedin_icon',
				'default_value' => '',
				'label'         => esc_html__( 'Upload Icon', 'overworld' ),
				'parent'        => $enable_linkedin_container
			)
		);
		
		/**
		 * Tumblr
		 */
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_social_networks,
				'name'   => 'tumblr_title',
				'title'  => esc_html__( 'Share on Tumblr', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_tumblr_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow sharing via Tumblr', 'overworld' ),
				'parent'        => $panel_social_networks
			)
		);
		
		$enable_tumblr_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_tumblr_container',
				'parent'          => $panel_social_networks,
				'dependency' => array(
					'show' => array(
						'enable_tumblr_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'tumblr_icon',
				'default_value' => '',
				'label'         => esc_html__( 'Upload Icon', 'overworld' ),
				'parent'        => $enable_tumblr_container
			)
		);
		
		/**
		 * Pinterest
		 */
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_social_networks,
				'name'   => 'pinterest_title',
				'title'  => esc_html__( 'Share on Pinterest', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_pinterest_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow sharing via Pinterest', 'overworld' ),
				'parent'        => $panel_social_networks
			)
		);
		
		$enable_pinterest_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_pinterest_container',
				'parent'          => $panel_social_networks,
				'dependency' => array(
					'show' => array(
						'enable_pinterest_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'pinterest_icon',
				'default_value' => '',
				'label'         => esc_html__( 'Upload Icon', 'overworld' ),
				'parent'        => $enable_pinterest_container
			)
		);
		
		/**
		 * VK
		 */
		overworld_edge_add_admin_section_title(
			array(
				'parent' => $panel_social_networks,
				'name'   => 'vk_title',
				'title'  => esc_html__( 'Share on VK', 'overworld' )
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_vk_share',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Share', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow sharing via VK', 'overworld' ),
				'parent'        => $panel_social_networks
			)
		);
		
		$enable_vk_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_vk_container',
				'parent'          => $panel_social_networks,
				'dependency' => array(
					'show' => array(
						'enable_vk_share'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'vk_icon',
				'default_value' => '',
				'label'         => esc_html__( 'Upload Icon', 'overworld' ),
				'parent'        => $enable_vk_container
			)
		);
		
		if ( defined( 'OVERWORLD_TWITTER_FEED_VERSION' ) ) {
			$twitter_panel = overworld_edge_add_admin_panel(
				array(
					'title' => esc_html__( 'Twitter', 'overworld' ),
					'name'  => 'panel_twitter',
					'page'  => '_social_page'
				)
			);
			
			overworld_edge_add_admin_twitter_button(
				array(
					'name'   => 'twitter_button',
					'parent' => $twitter_panel
				)
			);
		}
		
		if ( defined( 'OVERWORLD_INSTAGRAM_FEED_VERSION' ) ) {
			$instagram_panel = overworld_edge_add_admin_panel(
				array(
					'title' => esc_html__( 'Instagram', 'overworld' ),
					'name'  => 'panel_instagram',
					'page'  => '_social_page'
				)
			);
			
			overworld_edge_add_admin_instagram_button(
				array(
					'name'   => 'instagram_button',
					'parent' => $instagram_panel
				)
			);
		}
		
		/**
		 * Open Graph
		 */
		$panel_open_graph = overworld_edge_add_admin_panel(
			array(
				'page'  => '_social_page',
				'name'  => 'panel_open_graph',
				'title' => esc_html__( 'Open Graph', 'overworld' ),
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'yesno',
				'name'          => 'enable_open_graph',
				'default_value' => 'no',
				'label'         => esc_html__( 'Enable Open Graph', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will allow usage of Open Graph protocol on your site', 'overworld' ),
				'parent'        => $panel_open_graph
			)
		);
		
		$enable_open_graph_container = overworld_edge_add_admin_container(
			array(
				'name'            => 'enable_open_graph_container',
				'parent'          => $panel_open_graph,
				'dependency' => array(
					'show' => array(
						'enable_open_graph'  => 'yes'
					)
				)
			)
		);
		
		overworld_edge_add_admin_field(
			array(
				'type'          => 'image',
				'name'          => 'open_graph_image',
				'default_value' => OVERWORLD_EDGE_ASSETS_ROOT . '/img/open_graph.jpg',
				'label'         => esc_html__( 'Default Share Image', 'overworld' ),
				'parent'        => $enable_open_graph_container,
				'description'   => esc_html__( 'Used when featured image is not set. Make sure that image is at least 1200 x 630 pixels, up to 8MB in size', 'overworld' ),
			)
		);

        /**
         * Action for embedding social share option for custom post types
         */
        do_action('overworld_edge_action_social_options', $page);
	}
	
	add_action( 'overworld_edge_action_options_map', 'overworld_edge_social_options_map', overworld_edge_set_options_map_position( 'social' ) );
}