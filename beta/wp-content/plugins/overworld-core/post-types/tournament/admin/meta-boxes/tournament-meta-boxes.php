<?php

if (!function_exists('overworld_core_tournament_meta_box_map')) {
    function overworld_core_tournament_meta_box_map() {

        $tournament_meta_box = overworld_edge_create_meta_box(
            array(
                'scope' => array('tournament'),
                'title' => esc_html__('Tournament', 'overworld-core'),
                'name'  => 'tournament_meta'
            )
        );

        overworld_edge_add_admin_section_title(array(
            'name'   => 'tournament_general_title',
            'parent' => $tournament_meta_box,
            'title'  => esc_html__('General', 'overworld-core')
        ));


        overworld_edge_create_meta_box_field(
            array(
                'type'          => 'select',
                'name'          => 'edgtf_tournament_status_meta',
                'default_value' => 'to_be_played',
                'label'         => esc_html__('Tournament Status', 'overworld-core'),
                'description'   => esc_html__('Choose tournament status for this tournament', 'overworld-core'),
                'options'       => array(
	                'upcoming'    => esc_html__( 'Upcoming', 'overworld-core' ),
	                'in_progress' => esc_html__( 'In Progress', 'overworld-core' ),
	                'finished'    => esc_html__( 'Finished', 'overworld-core' ),
	                'canceled'    => esc_html__( 'Canceled', 'overworld-core' )
                ),
                'parent'        => $tournament_meta_box,
            )
        );

        overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_tournament_sponsor',
				'type'        => 'text',
				'label'       => esc_html__( 'Sponsor', 'overworld-core' ),
				'description' => esc_html__( 'The tournament\'s sponsor', 'overworld-core' ),
				'parent'      => $tournament_meta_box
			)
		);

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_tournament_sponsor_logo',
                'type'            => 'image',
                'label'           => esc_html__('Sponsor Logo', 'overworld-core'),
                'description'     => esc_html__('Upload sponsor logo', 'overworld-core'),
                'parent'          => $tournament_meta_box,
            )
        );

        overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_location_meta',
                'type'        => 'text',
                'label'       => esc_html__('Location', 'overworld-core'),
                'description' => esc_html__('Insert location for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_date_meta',
                'type'        => 'date',
                'label'       => esc_html__('Date', 'overworld-core'),
                'description' => esc_html__('Choose date for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

        overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_time_meta',
                'type'        => 'text',
                'label'       => esc_html__('Time', 'overworld-core'),
                'description' => esc_html__('Insert time for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_stream_link',
                'type'        => 'text',
                'label'       => esc_html__('Link for stream', 'overworld-core'),
                'description' => esc_html__('Set the live streaming link', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 12
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_prize_pool_meta',
                'type'        => 'text',
                'label'       => esc_html__('Prize Pool', 'overworld-core'),
                'description' => esc_html__('Insert prize pool for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_play_mode_meta',
                'type'        => 'text',
                'label'       => esc_html__('Play Mode', 'overworld-core'),
                'description' => esc_html__('Insert play mode for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_platform_meta',
                'type'        => 'text',
                'label'       => esc_html__('Platform', 'overworld-core'),
                'description' => esc_html__('Insert platform for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_tournament_platform_logo_meta',
                'type'            => 'image',
                'label'           => esc_html__('Platform Logo', 'overworld-core'),
                'description'     => esc_html__('Upload platform logo', 'overworld-core'),
                'parent'          => $tournament_meta_box,
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_tournament_players_number_meta',
                'type'        => 'text',
                'label'       => esc_html__('Players Number', 'overworld-core'),
                'description' => esc_html__('Insert players number for this tournament', 'overworld-core'),
                'parent'      => $tournament_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_tournament_bg_image_meta',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image For Tournament Singles', 'overworld-core'),
                'parent'          => $tournament_meta_box,
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_tournament_bg_image_shortcode_meta',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image For Tournament Shortcodes', 'overworld-core'),
                'parent'          => $tournament_meta_box,
            )
        );
    }

    add_action('overworld_edge_action_meta_boxes_map', 'overworld_core_tournament_meta_box_map', 46);
}