<?php

if ( ! function_exists( 'overworld_core_tournaments_meta_box_functions' ) ) {
    function overworld_core_tournaments_meta_box_functions( $post_types ) {
        $post_types[] = 'tournament';

        return $post_types;
    }

    add_filter( 'overworld_edge_filter_meta_box_post_types_save', 'overworld_core_tournaments_meta_box_functions' );
    add_filter( 'overworld_edge_filter_meta_box_post_types_remove', 'overworld_core_tournaments_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_tournaments_scope_meta_box_functions' ) ) {
    function overworld_core_tournaments_scope_meta_box_functions( $post_types ) {
        $post_types[] = 'tournament';

        return $post_types;
    }

    add_filter( 'overworld_edge_filter_set_scope_for_meta_boxes', 'overworld_core_tournaments_scope_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_tournament_enqueue_meta_box_styles' ) ) {
	function overworld_core_tournament_enqueue_meta_box_styles() {
		global $post;

		if ( ! empty( $post ) && $post->post_type == 'tournament' ) {
			wp_enqueue_style( 'edgtf-jquery-ui', get_template_directory_uri() . '/framework/admin/assets/css/jquery-ui/jquery-ui.css' );
		}
	}

	add_action( 'overworld_edge_action_enqueue_meta_box_styles', 'overworld_core_tournament_enqueue_meta_box_styles' );
}

if ( ! function_exists( 'overworld_core_register_tournaments_cpt' ) ) {
    function overworld_core_register_tournaments_cpt( $cpt_class_name ) {
        $cpt_class = array(
            'OverworldCore\CPT\Tournament\TournamentRegister'
        );

        $cpt_class_name = array_merge( $cpt_class_name, $cpt_class );

        return $cpt_class_name;
    }

    add_filter( 'overworld_core_filter_register_custom_post_types', 'overworld_core_register_tournaments_cpt' );
}

// Load tournaments shortcodes
if(!function_exists('overworld_core_include_tournaments_shortcodes_files')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function overworld_core_include_tournaments_shortcodes_files() {
        foreach(glob(OVERWORLD_CORE_CPT_PATH.'/tournament/shortcodes/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('overworld_core_action_include_shortcodes_file', 'overworld_core_include_tournaments_shortcodes_files');
}

// Load tournaments widgets
if(!function_exists('overworld_core_include_tournaments_widgets_files')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function overworld_core_include_tournaments_widgets_files() {
        foreach(glob(OVERWORLD_CORE_CPT_PATH.'/tournament/widgets/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('overworld_core_action_include_widgets_file', 'overworld_core_include_tournaments_widgets_files');
}

if ( ! function_exists( 'overworld_core_get_single_tournament' ) ) {
    function overworld_core_get_single_tournament() {
    	$tournament_id = get_the_ID();

        $params = array(
	        'sidebar'        => overworld_edge_sidebar_layout(),
	        'tournament_id'  => $tournament_id,
	        'status'         => get_post_meta( $tournament_id, 'edgtf_tournament_status_meta', true ),
	        'sponsor'        => get_post_meta( $tournament_id, 'edgtf_tournament_sponsor', true ),
	        'sponsor_logo'   => get_post_meta( $tournament_id, 'edgtf_tournament_sponsor_logo', true ),
	        'location'       => get_post_meta( $tournament_id, 'edgtf_tournament_location_meta', true ),
	        'date'           => get_post_meta( $tournament_id, 'edgtf_tournament_date_meta', true ),
	        'time'           => get_post_meta( $tournament_id, 'edgtf_tournament_time_meta', true ),
	        'stream_link'    => get_post_meta( $tournament_id, 'edgtf_tournament_stream_link', true ),
	        'prize_pool'     => get_post_meta( $tournament_id, 'edgtf_tournament_prize_pool_meta', true ),
	        'play_mode'      => get_post_meta( $tournament_id, 'edgtf_tournament_play_mode_meta', true ),
	        'platform'       => get_post_meta( $tournament_id, 'edgtf_tournament_platform_meta', true ),
	        'platform_logo'  => get_post_meta( $tournament_id, 'edgtf_tournament_platform_logo_meta', true ),
	        'players_number' => get_post_meta( $tournament_id, 'edgtf_tournament_players_number_meta', true )
        );

        overworld_core_get_cpt_single_module_template_part( 'templates/single/holder', 'tournament', '', $params);
    }
}

if ( ! function_exists( 'overworld_core_get_tournament_category_list' ) ) {
	function overworld_core_get_tournament_category_list( $category = '' ) {
		$number_of_columns = 3;

		$params = array(
			'number_of_columns' => $number_of_columns
		);

		if ( ! empty( $category ) ) {
			$params['category'] = $category;
		}

		$html = overworld_edge_execute_shortcode( 'edgtf_tournament_list', $params );

		echo overworld_edge_get_module_part($html);
	}
}

if ( ! function_exists( 'overworld_core_add_tournament_to_search_types' ) ) {
	function overworld_core_add_tournament_to_search_types( $post_types ) {
		$post_types['tournament'] = esc_html__( 'Tournament', 'overworld-core' );

		return $post_types;
	}

	add_filter( 'overworld_edge_filter_search_post_type_widget_params_post_type', 'overworld_core_add_tournament_to_search_types' );
}