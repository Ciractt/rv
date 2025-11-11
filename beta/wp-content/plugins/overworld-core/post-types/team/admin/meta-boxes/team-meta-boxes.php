<?php

if (!function_exists('overworld_core_team_meta_box_map')) {
    function overworld_core_team_meta_box_map() {

        $team_meta_box = overworld_edge_create_meta_box(
            array(
                'scope' => array('team'),
                'title' => esc_html__('Team', 'overworld-core'),
                'name'  => 'team_meta'
            )
        );

        overworld_edge_add_admin_section_title(array(
            'name'   => 'team_general_title',
            'parent' => $team_meta_box,
            'title'  => esc_html__('General', 'overworld-core')
        ));

        overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_team_sponsor',
				'type'        => 'text',
				'label'       => esc_html__( 'Sponsor', 'overworld-core' ),
				'description' => esc_html__( 'The team\'s sponsor', 'overworld-core' ),
				'parent'      => $team_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_team_sponsor_logo',
                'type'            => 'image',
                'label'           => esc_html__('Sponsor Logo', 'overworld-core'),
                'description'     => esc_html__('Upload sponsor logo', 'overworld-core'),
                'parent'          => $team_meta_box,
            )
        );

        for ( $x = 1; $x < 6; $x ++ ) {

			$social_icon_group = overworld_edge_add_admin_group(
				array(
					'name'   => 'edgtf_team_social_icon_group' . $x,
					'title'  => esc_html__( 'Social Link ', 'overworld-core' ) . $x,
					'parent' => $team_meta_box
				)
			);

			$social_row1 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_team_social_icon_row1' . $x,
					'parent' => $social_icon_group
				)
			);

			overworld_edge_icon_collections()->getIconsMetaBoxOrOption(
				array(
					'label'            => esc_html__( 'Icon ', 'overworld-core' ) . $x,
					'parent'           => $social_row1,
					'name'             => 'edgtf_team_social_icon_pack_' . $x,
					'defaul_icon_pack' => '',
					'type'             => 'meta-box',
					'field_type'       => 'simple'
				)
			);

			$social_row2 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_team_social_icon_row2' . $x,
					'parent' => $social_icon_group
				)
			);

			overworld_edge_create_meta_box_field(
				array(
					'type'            => 'textsimple',
					'label'           => esc_html__( 'Link', 'overworld-core' ),
					'name'            => 'edgtf_team_social_icon_' . $x . '_link',
					'parent'          => $social_row2,
					'dependency' => array(
						'hide' => array(
							'edgtf_team_social_icon_pack_'. $x  => ''
						)
					)
				)
			);

			overworld_edge_create_meta_box_field(
				array(
					'type'            => 'selectsimple',
					'label'           => esc_html__( 'Target', 'overworld-core' ),
					'name'            => 'edgtf_team_social_icon_' . $x . '_target',
					'options'         => overworld_edge_get_link_target_array(),
					'parent'          => $social_row2,
					'dependency' => array(
						'hide' => array(
							'edgtf_team_social_icon_pack_'. $x  => ''
						)
					)
				)
			);
		}

		for ( $x = 1; $x < 3; $x ++ ) {

			$special_link_group = overworld_edge_add_admin_group(
				array(
					'name'   => 'edgtf_team_special_link_group' . $x,
					'title'  => esc_html__( 'Special Link ', 'overworld-core' ) . $x,
					'parent' => $team_meta_box
				)
			);

			$special_link_row1 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_team_special_link_row1' . $x,
					'parent' => $special_link_group
				)
			);

			overworld_edge_icon_collections()->getIconsMetaBoxOrOption(
				array(
					'label'            => esc_html__( 'Icon ', 'overworld-core' ) . $x,
					'parent'           => $special_link_row1,
					'name'             => 'edgtf_team_special_link_icon_pack_' . $x,
					'defaul_icon_pack' => '',
					'type'             => 'meta-box',
					'field_type'       => 'simple'
				)
			);

			$special_link_row2 = overworld_edge_add_admin_row(
				array(
					'name'   => 'edgtf_team_special_link_row2' . $x,
					'parent' => $special_link_group
				)
			);

			overworld_edge_create_meta_box_field(
				array(
					'type'            => 'textsimple',
					'label'           => esc_html__( 'Link', 'overworld-core' ),
					'name'            => 'edgtf_team_special_link_' . $x . '_link',
					'parent'          => $special_link_row2,
					'dependency' => array(
						'hide' => array(
							'edgtf_team_special_link_icon_pack_'. $x  => ''
						)
					)
				)
			);

			overworld_edge_create_meta_box_field(
				array(
					'type'            => 'selectsimple',
					'label'           => esc_html__( 'Target', 'overworld-core' ),
					'name'            => 'edgtf_team_special_link_' . $x . '_target',
					'options'         => overworld_edge_get_link_target_array(),
					'parent'          => $special_link_row2,
					'dependency' => array(
						'hide' => array(
							'edgtf_team_special_link_icon_pack_'. $x  => ''
						)
					)
				)
			);
		}

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_team_bg_image_meta',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'parent'          => $team_meta_box,
            )
        );
    }

    add_action('overworld_edge_action_meta_boxes_map', 'overworld_core_team_meta_box_map', 46);
}