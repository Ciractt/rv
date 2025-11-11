<?php

if ( ! function_exists( 'overworld_core_team_meta_box_functions' ) ) {
    function overworld_core_team_meta_box_functions( $post_types ) {
        $post_types[] = 'team';

        return $post_types;
    }

    add_filter( 'overworld_edge_filter_meta_box_post_types_save', 'overworld_core_team_meta_box_functions' );
    add_filter( 'overworld_edge_filter_meta_box_post_types_remove', 'overworld_core_team_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_team_scope_meta_box_functions' ) ) {
    function overworld_core_team_scope_meta_box_functions( $post_types ) {
        $post_types[] = 'team';

        return $post_types;
    }

    add_filter( 'overworld_edge_filter_set_scope_for_meta_boxes', 'overworld_core_team_scope_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_register_team_cpt' ) ) {
    function overworld_core_register_team_cpt( $cpt_class_name ) {
        $cpt_class = array(
            'OverworldCore\CPT\Team\TeamRegister'
        );

        $cpt_class_name = array_merge( $cpt_class_name, $cpt_class );

        return $cpt_class_name;
    }

    add_filter( 'overworld_core_filter_register_custom_post_types', 'overworld_core_register_team_cpt' );
}

// Load team shortcodes
if(!function_exists('overworld_core_include_team_shortcodes_files')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function overworld_core_include_team_shortcodes_files() {
        foreach(glob(OVERWORLD_CORE_CPT_PATH.'/team/shortcodes/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('overworld_core_action_include_shortcodes_file', 'overworld_core_include_team_shortcodes_files');
}

// Load team widgets
if(!function_exists('overworld_core_include_team_widgets_files')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function overworld_core_include_team_widgets_files() {
        foreach(glob(OVERWORLD_CORE_CPT_PATH.'/team/widgets/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('overworld_core_action_include_widgets_file', 'overworld_core_include_team_widgets_files');
}

if ( ! function_exists( 'overworld_core_get_single_team' ) ) {
    function overworld_core_get_single_team() {
	    $team_id = get_the_ID();

        $params = array(
	        'sidebar'      => overworld_edge_sidebar_layout(),
	        'team_id'      => $team_id,
	        'sponsor'      => get_post_meta( $team_id, 'edgtf_team_sponsor', true ),
	        'sponsor_logo' => get_post_meta( $team_id, 'edgtf_team_sponsor_logo', true ),
	        'social_icons' => overworld_core_single_team_social_icons( $team_id )
        );

        overworld_core_get_cpt_single_module_template_part( 'templates/single/holder', 'team', '', $params);
    }
}

if ( ! function_exists( 'overworld_core_single_team_social_icons' ) ) {
	function overworld_core_single_team_social_icons( $id ) {
		$social_icons = array();

		for ( $i = 1; $i < 6; $i ++ ) {
			$team_icon_pack = get_post_meta( $id, 'edgtf_team_social_icon_pack_' . $i, true );
			if ( $team_icon_pack !== '' ) {
				$team_icon_collection = overworld_edge_icon_collections()->getIconCollection( get_post_meta( $id, 'edgtf_team_social_icon_pack_' . $i, true ) );
				$team_social_icon     = get_post_meta( $id, 'edgtf_team_social_icon_pack_' . $i . '_' . $team_icon_collection->param, true );
				$team_social_link     = get_post_meta( $id, 'edgtf_team_social_icon_' . $i . '_link', true );
				$team_social_target   = get_post_meta( $id, 'edgtf_team_social_icon_' . $i . '_target', true );

				if ( $team_social_icon !== '' ) {
					$team_icon_params                                 = array();
					$team_icon_params['icon_pack']                    = $team_icon_pack;
					$team_icon_params[ $team_icon_collection->param ] = $team_social_icon;
					$team_icon_params['link']                         = ! empty( $team_social_link ) ? $team_social_link : '';
					$team_icon_params['target']                       = ! empty( $team_social_target ) ? $team_social_target : '_self';

					$social_icons[] = overworld_edge_execute_shortcode( 'edgtf_icon', $team_icon_params );
				}
			}
		}

		return $social_icons;
	}
}

if ( ! function_exists( 'overworld_core_get_team_category_list' ) ) {
	function overworld_core_get_team_category_list( $category = '' ) {
		$number_of_columns = 3;

		$params = array(
			'number_of_columns' => $number_of_columns
		);

		if ( ! empty( $category ) ) {
			$params['category'] = $category;
		}

		$html = overworld_edge_execute_shortcode( 'edgtf_team_list', $params );

		echo overworld_edge_get_module_part($html);
	}
}

if ( ! function_exists( 'overworld_core_add_team_to_search_types' ) ) {
	function overworld_core_add_team_to_search_types( $post_types ) {
		$post_types['team'] = esc_html__( 'Team', 'overworld-core' );

		return $post_types;
	}

	add_filter( 'overworld_edge_filter_search_post_type_widget_params_post_type', 'overworld_core_add_team_to_search_types' );
}

if ( ! function_exists( 'overworld_core_calculate_team_stats' ) ) {
	function overworld_core_calculate_team_stats( $team_id ) {

		$team_id = intval($team_id);

		$team_stats = array(
			'wins'  => 0,
			'loses' => 0,
			'draws' => 0,
			'total' => 0
		);
		$matches = get_posts([
			'post_type'   => 'match',
			'post_status' => 'publish',
			'meta_query' => array(
				'relation' => 'AND',
		        array(
		            'key' => 'edgtf_match_status_meta',
		            'value' => 'finished',
		            'compare' => '='
		        ),
				array(
					'relation' => 'OR',
			        array(
			            'key' => 'edgtf_match_team_1',
			            'value' => $team_id,
			            'compare' => '='
			        ),
					array(
			            'key' => 'edgtf_match_team_2',
			            'value' => $team_id,
			            'compare' => '='
			        )
			    )
		    )
		]);
		foreach ( $matches as $match ) {

			if ($match->edgtf_match_team_1 == $team_id) {
				$teams_score = floatval($match->edgtf_match_team_1_score_meta);
				$other_score = floatval($match->edgtf_match_team_2_score_meta);
			} else {
				$teams_score = floatval($match->edgtf_match_team_2_score_meta);
				$other_score = floatval($match->edgtf_match_team_1_score_meta);
			}

			if ($teams_score > $other_score) {
				$team_stats['wins']++;
			} else if ($teams_score < $other_score) {
				$team_stats['loses']++;
			} else if ($teams_score == $other_score) {
				$team_stats['draws']++;
			}

			$team_stats['total']++;
		}

		return $team_stats;
	}
}