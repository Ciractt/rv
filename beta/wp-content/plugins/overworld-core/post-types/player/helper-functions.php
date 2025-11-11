<?php

if ( ! function_exists( 'overworld_core_player_meta_box_functions' ) ) {
	function overworld_core_player_meta_box_functions( $post_types ) {
		$post_types[] = 'player';
		
		return $post_types;
	}
	
	add_filter( 'overworld_edge_filter_meta_box_post_types_save', 'overworld_core_player_meta_box_functions' );
	add_filter( 'overworld_edge_filter_meta_box_post_types_remove', 'overworld_core_player_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_player_scope_meta_box_functions' ) ) {
	function overworld_core_player_scope_meta_box_functions( $post_types ) {
		$post_types[] = 'player';
		
		return $post_types;
	}
	
	add_filter( 'overworld_edge_filter_set_scope_for_meta_boxes', 'overworld_core_player_scope_meta_box_functions' );
}

if ( ! function_exists( 'overworld_core_player_enqueue_meta_box_styles' ) ) {
	function overworld_core_player_enqueue_meta_box_styles() {
		global $post;
		
		if ( ! empty( $post ) && $post->post_type == 'player' ) {
			wp_enqueue_style( 'edgtf-jquery-ui', get_template_directory_uri() . '/framework/admin/assets/css/jquery-ui/jquery-ui.css' );
		}
	}
	
	add_action( 'overworld_edge_action_enqueue_meta_box_styles', 'overworld_core_player_enqueue_meta_box_styles' );
}

if ( ! function_exists( 'overworld_core_register_player_cpt' ) ) {
	function overworld_core_register_player_cpt( $cpt_class_name ) {
		$cpt_class = array(
			'OverworldCore\CPT\Player\PlayerRegister'
		);
		
		$cpt_class_name = array_merge( $cpt_class_name, $cpt_class );
		
		return $cpt_class_name;
	}
	
	add_filter( 'overworld_core_filter_register_custom_post_types', 'overworld_core_register_player_cpt' );
}

// Load player shortcodes
if ( ! function_exists( 'overworld_core_include_player_shortcodes_files' ) ) {
	/**
	 * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
	 */
	function overworld_core_include_player_shortcodes_files() {
		foreach ( glob( OVERWORLD_CORE_CPT_PATH . '/player/shortcodes/*/load.php' ) as $shortcode_load ) {
			include_once $shortcode_load;
		}
	}
	
	add_action( 'overworld_core_action_include_shortcodes_file', 'overworld_core_include_player_shortcodes_files' );
}

if ( ! function_exists( 'overworld_core_get_single_player' ) ) {
	/**
	 * Loads holder template for player single
	 */
	function overworld_core_get_single_player() {
		$player_id = get_the_ID();
		
		$params = array(
			'sidebar'          => overworld_edge_sidebar_layout(),
			'player_id'        => $player_id,
			'nickname'         => get_post_meta( $player_id, 'edgtf_player_nickname', true ),
			'birth_date'       => get_post_meta( $player_id, 'edgtf_player_birth_date', true ),
			'team'             => get_post_meta( $player_id, 'edgtf_player_team', true ),
			'role'             => get_post_meta( $player_id, 'edgtf_player_role', true ),
			'nationality'      => get_post_meta( $player_id, 'edgtf_player_nationality', true ),
			'nationality_flag' => get_post_meta( $player_id, 'edgtf_player_nationality_flag', true ),
			'social_icons'     => overworld_core_single_player_social_icons( $player_id )
		);
		
		overworld_core_get_cpt_single_module_template_part( 'templates/single/holder', 'player', '', $params );
	}
}

if ( ! function_exists( 'overworld_core_single_player_social_icons' ) ) {
	function overworld_core_single_player_social_icons( $id ) {
		$social_icons = array();
		
		for ( $i = 1; $i < 6; $i ++ ) {
			$player_icon_pack = get_post_meta( $id, 'edgtf_player_social_icon_pack_' . $i, true );
			if ( $player_icon_pack !== '' ) {
				$player_icon_collection = overworld_edge_icon_collections()->getIconCollection( get_post_meta( $id, 'edgtf_player_social_icon_pack_' . $i, true ) );
				$player_social_icon     = get_post_meta( $id, 'edgtf_player_social_icon_pack_' . $i . '_' . $player_icon_collection->param, true );
				$player_social_link     = get_post_meta( $id, 'edgtf_player_social_icon_' . $i . '_link', true );
				$player_social_target   = get_post_meta( $id, 'edgtf_player_social_icon_' . $i . '_target', true );
				
				if ( $player_social_icon !== '' ) {
					$player_icon_params                                 = array();
					$player_icon_params['icon_pack']                    = $player_icon_pack;
					$player_icon_params[ $player_icon_collection->param ] = $player_social_icon;
					$player_icon_params['link']                         = ! empty( $player_social_link ) ? $player_social_link : '';
					$player_icon_params['target']                       = ! empty( $player_social_target ) ? $player_social_target : '_self';
					
					$social_icons[] = overworld_edge_execute_shortcode( 'edgtf_icon', $player_icon_params );
				}
			}
		}
		
		return $social_icons;
	}
}

if ( ! function_exists( 'overworld_core_get_player_category_list' ) ) {
	function overworld_core_get_player_category_list( $category = '' ) {
		$number_of_columns = 3;
		
		$params = array(
			'number_of_columns' => $number_of_columns
		);
		
		if ( ! empty( $category ) ) {
			$params['category'] = $category;
		}
		
		$html = overworld_edge_execute_shortcode( 'edgtf_player_list', $params );

		echo overworld_edge_get_module_part($html);
	}
}

if ( ! function_exists( 'overworld_core_add_player_to_search_types' ) ) {
	function overworld_core_add_player_to_search_types( $post_types ) {
		$post_types['player'] = esc_html__( 'Player', 'overworld-core' );
		
		return $post_types;
	}
	
	add_filter( 'overworld_edge_filter_search_post_type_widget_params_post_type', 'overworld_core_add_player_to_search_types' );
}