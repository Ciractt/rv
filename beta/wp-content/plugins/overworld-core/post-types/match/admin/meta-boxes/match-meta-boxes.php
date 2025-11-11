<?php

if (!function_exists('overworld_core_match_meta_box_map')) {
    function overworld_core_match_meta_box_map() {

        $match_meta_box = overworld_edge_create_meta_box(
            array(
                'scope' => array('match'),
                'title' => esc_html__('Match', 'overworld-core'),
                'name'  => 'match_meta'
            )
        );

        overworld_edge_add_admin_section_title(array(
            'name'   => 'match_general_title',
            'parent' => $match_meta_box,
            'title'  => esc_html__('General', 'overworld-core')
        ));


        overworld_edge_create_meta_box_field(
            array(
                'type'          => 'select',
                'name'          => 'edgtf_match_status_meta',
                'default_value' => 'to_be_played',
                'label'         => esc_html__('Match Status', 'overworld-core'),
                'description'   => esc_html__('Choose match status for this match', 'overworld-core'),
                'options'       => array(
	                'upcoming'    => esc_html__( 'Upcoming', 'overworld-core' ),
	                'in_progress' => esc_html__( 'In Progress', 'overworld-core' ),
	                'finished'    => esc_html__( 'Finished', 'overworld-core' ),
	                'canceled'    => esc_html__( 'Canceled', 'overworld-core' )
                ),
                'parent'        => $match_meta_box,
            )
        );

        $all_tournaments = array(
        	'' => esc_html__('No Tournament', 'overworld-core')
        );
		$tournaments     = get_posts([
			'post_type'   => 'tournament',
			'post_status' => 'publish',
			'numberposts' => - 1,
			'order'       => 'ASC'
		]);
		foreach ( $tournaments as $tournament ) {
			$all_tournaments[ $tournament->ID ] = $tournament->post_title;
		}

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_match_tournament',
				'type'        => 'select',
				'label'       => esc_html__( 'Tournament', 'overworld-core' ),
				'description' => esc_html__( 'Choose a Tournament for this match', 'overworld-core' ),
				'parent'      => $match_meta_box,
				'options'     => $all_tournaments,
				'args'        => array(
					'select2' => true
				)
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
				'name'        => 'edgtf_match_team_1',
				'type'        => 'select',
				'label'       => esc_html__( 'Team 1', 'overworld-core' ),
				'description' => esc_html__( 'Choose a Team 1 for this match', 'overworld-core' ),
				'parent'      => $match_meta_box,
				'options'     => $all_teams,
				'args'        => array(
					'select2' => true
				)
			)
		);

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_match_team_1_score_meta',
                'type'        => 'text',
                'label'       => esc_html__('Team 1 Score', 'overworld-core'),
                'description' => esc_html__('Insert match score for team 1', 'overworld-core'),
                'parent'      => $match_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_match_team_2',
				'type'        => 'select',
				'label'       => esc_html__( 'Team 2', 'overworld-core' ),
				'description' => esc_html__( 'Choose a Team 2 for this match', 'overworld-core' ),
				'parent'      => $match_meta_box,
				'options'     => $all_teams,
				'args'        => array(
					'select2' => true
				)
			)
		);

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_match_team_2_score_meta',
                'type'        => 'text',
                'label'       => esc_html__('Team 2 Score', 'overworld-core'),
                'description' => esc_html__('Insert match score for team 2', 'overworld-core'),
                'parent'      => $match_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

		overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_match_date_meta',
                'type'        => 'date',
                'label'       => esc_html__('Date', 'overworld-core'),
                'description' => esc_html__('Choose date for this match', 'overworld-core'),
                'parent'      => $match_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );

        overworld_edge_create_meta_box_field(
            array(
                'name'        => 'edgtf_match_time_meta',
                'type'        => 'text',
                'label'       => esc_html__('Time', 'overworld-core'),
                'description' => esc_html__('Insert time for this match', 'overworld-core'),
                'parent'      => $match_meta_box,
                'args'        => array(
                    'col_width' => 2
                )
            )
        );
	    
		overworld_edge_create_meta_box_field(
            array(
                'name'            => 'edgtf_match_bg_image_meta',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'parent'          => $match_meta_box,
            )
        );
    }

    add_action('overworld_edge_action_meta_boxes_map', 'overworld_core_match_meta_box_map', 46);
}