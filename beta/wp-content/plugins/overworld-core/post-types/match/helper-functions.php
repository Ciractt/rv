<?php

if ( ! function_exists( 'overworld_core_matches_meta_box_functions' ) ) {
    function overworld_core_matches_meta_box_functions( $post_types ) {
        $post_types[] = 'match';

        return $post_types;
    }

    add_filter( 'overworld_edge_filter_meta_box_post_types_save', 'overworld_core_matches_meta_box_functions' );
    add_filter( 'overworld_edge_filter_meta_box_post_types_remove', 'overworld_core_matches_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_matches_scope_meta_box_functions' ) ) {
    function overworld_core_matches_scope_meta_box_functions( $post_types ) {
        $post_types[] = 'match';

        return $post_types;
    }

    add_filter( 'overworld_edge_filter_set_scope_for_meta_boxes', 'overworld_core_matches_scope_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_match_enqueue_meta_box_styles' ) ) {
	function overworld_core_match_enqueue_meta_box_styles() {
		global $post;

		if ( ! empty( $post ) && $post->post_type == 'match' ) {
			wp_enqueue_style( 'edgtf-jquery-ui', get_template_directory_uri() . '/framework/admin/assets/css/jquery-ui/jquery-ui.css' );
		}
	}

	add_action( 'overworld_edge_action_enqueue_meta_box_styles', 'overworld_core_match_enqueue_meta_box_styles' );
}

if ( ! function_exists( 'overworld_core_match_add_social_share_option' ) ) {
    function overworld_core_match_add_social_share_option( $container ) {
        overworld_edge_add_admin_field(
            array(
                'type'          => 'yesno',
                'name'          => 'enable_social_share_on_match',
                'default_value' => 'no',
                'label'         => esc_html__( 'Single Match', 'overworld-core' ),
                'description'   => esc_html__( 'Show Social Share on Single Matches', 'overworld-core' ),
                'parent'        => $container
            )
        );
    }

    add_action( 'overworld_edge_action_post_types_social_share', 'overworld_core_match_add_social_share_option', 10, 1 );
}

if ( ! function_exists( 'overworld_core_register_matches_cpt' ) ) {
    function overworld_core_register_matches_cpt( $cpt_class_name ) {
        $cpt_class = array(
            'OverworldCore\CPT\Match\MatchRegister'
        );

        $cpt_class_name = array_merge( $cpt_class_name, $cpt_class );

        return $cpt_class_name;
    }

    add_filter( 'overworld_core_filter_register_custom_post_types', 'overworld_core_register_matches_cpt' );
}

// Load matches shortcodes
if(!function_exists('overworld_core_include_matches_shortcodes_files')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function overworld_core_include_matches_shortcodes_files() {
        foreach(glob(OVERWORLD_CORE_CPT_PATH.'/match/shortcodes/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('overworld_core_action_include_shortcodes_file', 'overworld_core_include_matches_shortcodes_files');
}

// Load matches widgets
if(!function_exists('overworld_core_include_matches_widgets_files')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function overworld_core_include_matches_widgets_files() {
        foreach(glob(OVERWORLD_CORE_CPT_PATH.'/match/widgets/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('overworld_core_action_include_widgets_file', 'overworld_core_include_matches_widgets_files');
}

if ( ! function_exists( 'overworld_core_get_single_match' ) ) {
    function overworld_core_get_single_match() {
	    $match_id = get_the_ID();

        $params = array(
	        'sidebar'      => overworld_edge_sidebar_layout(),
	        'match_id'     => $match_id,
	        'status'       => get_post_meta( $match_id, 'edgtf_match_status_meta', true ),
	        'tournament'   => get_post_meta( $match_id, 'edgtf_match_tournament', true ),
	        'team_1'       => get_post_meta( $match_id, 'edgtf_match_team_1', true ),
	        'team_1_score' => get_post_meta( $match_id, 'edgtf_match_team_1_score_meta', true ),
	        'team_2'       => get_post_meta( $match_id, 'edgtf_match_team_2', true ),
	        'team_2_score' => get_post_meta( $match_id, 'edgtf_match_team_2_score_meta', true ),
	        'date'         => get_post_meta( $match_id, 'edgtf_match_date_meta', true ),
	        'time'         => get_post_meta( $match_id, 'edgtf_match_time_meta', true )
        );

        overworld_core_get_cpt_single_module_template_part( 'templates/single/holder', 'match', '', $params);
    }
}

if ( ! function_exists( 'overworld_core_get_match_category_list' ) ) {
	function overworld_core_get_match_category_list( $category = '' ) {
		$number_of_columns = 3;

		$params = array(
			'number_of_columns' => $number_of_columns
		);

		if ( ! empty( $category ) ) {
			$params['category'] = $category;
		}

		$html = overworld_edge_execute_shortcode( 'edgtf_match_list', $params );

		echo overworld_edge_get_module_part($html);
	}
}

if ( ! function_exists( 'overworld_core_add_match_to_search_types' ) ) {
	function overworld_core_add_match_to_search_types( $post_types ) {
		$post_types['match'] = esc_html__( 'Match', 'overworld-core' );

		return $post_types;
	}

	add_filter( 'overworld_edge_filter_search_post_type_widget_params_post_type', 'overworld_core_add_match_to_search_types' );
}