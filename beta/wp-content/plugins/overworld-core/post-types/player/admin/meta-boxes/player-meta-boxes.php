<?php

if ( ! function_exists( 'overworld_core_map_player_single_meta' ) ) {
	function overworld_core_map_player_single_meta() {
		
		$player_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => 'player',
				'title' => esc_html__( 'Player Info', 'overworld-core' ),
				'name'  => 'player_meta'
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_player_nickname',
				'type'        => 'text',
				'label'       => esc_html__( 'Nickname', 'overworld-core' ),
				'description' => esc_html__( 'The players\'s nickname', 'overworld-core' ),
				'parent'      => $player_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_player_birth_date',
				'type'        => 'date',
				'label'       => esc_html__( 'Birth date', 'overworld-core' ),
				'description' => esc_html__( 'The players\'s birth date', 'overworld-core' ),
				'parent'      => $player_meta_box
			)
		);

		$all_teams = array();
		$teams     = get_posts([
			'post_type'   => 'team',
			'post_status' => 'publish',
			'numberposts' => - 1,
			'order'       => 'ASC'
		]);
		foreach ( $teams as $team ) {
			$all_teams[ $team->ID ] = $team->post_title;
		}

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_player_team',
				'type'        => 'select',
				'label'       => esc_html__( 'Team', 'overworld-core' ),
				'description' => esc_html__( 'The players\'s team', 'overworld-core' ),
				'parent'      => $player_meta_box,
				'options'     => $all_teams,
				'args'        => array(
					'select2' => true
				)
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_player_role',
				'type'        => 'text',
				'label'       => esc_html__( 'Role', 'overworld-core' ),
				'description' => esc_html__( 'The players\'s role within the team', 'overworld-core' ),
				'parent'      => $player_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_player_nationality',
				'type'        => 'text',
				'label'       => esc_html__( 'Nationality', 'overworld-core' ),
				'description' => esc_html__( 'The players\'s nationality', 'overworld-core' ),
				'parent'      => $player_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_player_nationality_flag',
                'type'            => 'image',
                'label'           => esc_html__('Nationality Flag', 'overworld-core'),
                'description'     => esc_html__('Upload nationality flag', 'overworld-core'),
                'parent'          => $player_meta_box,
            )
        );
		
		for ( $x = 1; $x < 6; $x ++ ) {
			
			$social_icon_group = overworld_edge_add_admin_group(
				array(
					'name'   => 'edgtf_player_social_icon_group' . $x,
					'title'  => esc_html__( 'Social Link ', 'overworld-core' ) . $x,
					'parent' => $player_meta_box
				)
			);
			
			$social_row1 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_player_social_icon_row1' . $x,
					'parent' => $social_icon_group
				)
			);
			
			overworld_edge_icon_collections()->getIconsMetaBoxOrOption(
				array(
					'label'            => esc_html__( 'Icon ', 'overworld-core' ) . $x,
					'parent'           => $social_row1,
					'name'             => 'edgtf_player_social_icon_pack_' . $x,
					'defaul_icon_pack' => '',
					'type'             => 'meta-box',
					'field_type'       => 'simple'
				)
			);
			
			$social_row2 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_player_social_icon_row2' . $x,
					'parent' => $social_icon_group
				)
			);
			
			overworld_edge_create_meta_box_field(
				array(
					'type'            => 'textsimple',
					'label'           => esc_html__( 'Link', 'overworld-core' ),
					'name'            => 'edgtf_player_social_icon_' . $x . '_link',
					'parent'          => $social_row2,
					'dependency' => array(
						'hide' => array(
							'edgtf_player_social_icon_pack_'. $x  => ''
						)
					)
				)
			);
			
			overworld_edge_create_meta_box_field(
				array(
					'type'            => 'selectsimple',
					'label'           => esc_html__( 'Target', 'overworld-core' ),
					'name'            => 'edgtf_player_social_icon_' . $x . '_target',
					'options'         => overworld_edge_get_link_target_array(),
					'parent'          => $social_row2,
					'dependency' => array(
						'hide' => array(
							'edgtf_player_social_icon_pack_'. $x  => ''
						)
					)
				)
			);
		}

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_player_bg_image_meta',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'parent'          => $player_meta_box,
            )
        );
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_core_map_player_single_meta', 46 );
}