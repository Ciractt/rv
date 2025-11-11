<?php
namespace OverworldCore\CPT\Shortcodes\Player;

use OverworldCore\Lib;

class PlayerList implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_player_list';

        add_action('vc_before_init', array($this, 'vcMap'));

	    //Player category filter
	    add_filter( 'vc_autocomplete_edgtf_player_list_category_callback', array( &$this, 'playerCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Player category render
	    add_filter( 'vc_autocomplete_edgtf_player_list_category_render', array( &$this, 'playerCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Player selected projects filter
	    add_filter( 'vc_autocomplete_edgtf_player_list_selected_players_callback', array( &$this, 'playerIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Player selected projects render
	    add_filter( 'vc_autocomplete_edgtf_player_list_selected_players_render', array( &$this, 'playerIdAutocompleteRender', ), 10, 1 ); // Render exact player. Must return an array (label,value)
    }

    /**
     * Returns base for shortcode
     * @return string
     */
    public function getBase() {
        return $this->base;
    }

    /**
     * Maps shortcode to Visual Composer
     */
    public function vcMap() {
	    if(function_exists('vc_map')) {
		    vc_map(
		    	array(
				    'name'                      => esc_html__( 'Player List', 'overworld-core' ),
				    'base'                      => $this->getBase(),
				    'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
				    'icon'                      => 'icon-wpb-player-list extended-custom-icon',
				    'allowed_container_element' => 'vc_row',
				    'params'                    => array(
					    array(
						    'type'        => 'dropdown',
						    'param_name'  => 'number_of_columns',
						    'heading'     => esc_html__( 'Number of Columns', 'overworld-core' ),
						    'value'       => array_flip( overworld_edge_get_number_of_columns_array( true ) ),
						    'description' => esc_html__( 'Default value is Three', 'overworld-core' )
					    ),
                        array(
                            'type'        => 'dropdown',
                            'param_name'  => 'space_between_items',
                            'heading'     => esc_html__( 'Space Between Items', 'overworld-core' ),
                            'value'       => array_flip( overworld_edge_get_space_between_items_array() ),
                            'save_always' => true
                        ),
					    array(
						    'type'        => 'textfield',
						    'param_name'  => 'number_of_items',
						    'heading'     => esc_html__( 'Number of players per page', 'overworld-core' ),
						    'description' => esc_html__( 'Set number of items for your player list. Enter -1 to show all.', 'overworld-core' ),
						    'value'       => '-1'
					    ),
					    array(
						    'type'        => 'autocomplete',
						    'param_name'  => 'category',
						    'heading'     => esc_html__( 'One-Category Player List', 'overworld-core' ),
						    'description' => esc_html__( 'Enter one category slug (leave empty for showing all categories)', 'overworld-core' )
					    ),
					    array(
						    'type'        => 'autocomplete',
						    'param_name'  => 'selected_players',
						    'heading'     => esc_html__( 'Show Only Players with Listed IDs', 'overworld-core' ),
						    'settings'    => array(
							    'multiple'      => true,
							    'sortable'      => true,
							    'unique_values' => true
						    ),
						    'description' => esc_html__( 'Delimit ID numbers by comma (leave empty for all)', 'overworld-core' )
					    ),
					    array(
						    'type'        => 'dropdown',
						    'param_name'  => 'orderby',
						    'heading'     => esc_html__('Order By', 'overworld-core'),
						    'value'       => array_flip(overworld_edge_get_query_order_by_array()),
						    'save_always' => true
					    ),
					    array(
						    'type'       => 'dropdown',
						    'param_name' => 'order',
						    'heading'    => esc_html__('Order', 'overworld-core'),
						    'value'      => array_flip(overworld_edge_get_query_order_array()),
						    'save_always' => true
					    )
				    )
			    )
		    );
	    }
    }

    /**
     * Renders shortcodes HTML
     *
     * @param $atts array of shortcode params
     * @param $content string shortcode content
     *
     * @return string
     */
	public function render( $atts, $content = null ) {
		$args   = array(
			'number_of_columns'   => 'three',
			'space_between_items' => 'normal',
			'number_of_items'     => '-1',
			'category'            => '',
			'selected_players'    => '',
			'tag'                 => '',
			'orderby'             => 'date',
			'order'               => 'ASC',
			'player_slider'       => 'no',
			'slider_navigation'   => 'no',
			'slider_pagination'   => 'no'
		);
		$params = shortcode_atts( $args, $atts );
		
		/***
		 * @params query_results
		 * @params holder_data
		 * @params holder_classes
		 */
		$additional_params = array();
		
		$query_array                        = $this->getQueryArray( $params );
		$query_results                      = new \WP_Query( $query_array );
		$additional_params['query_results'] = $query_results;
		
		$additional_params['holder_classes'] = $this->getHolderClasses( $params, $args );
		$additional_params['inner_classes']  = $this->getInnerClasses( $params );
		$additional_params['data_attrs']     = $this->getDataAttribute( $params, $args );
		
		$params['this_object'] = $this;

		$html = overworld_core_get_cpt_shortcode_module_template_part( 'player', 'player-list', 'player-holder', '', $params, $additional_params );
		
		return $html;
	}
	
	/**
	 * Generates player list query attribute array
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public function getQueryArray( $params ) {
		$query_array = array(
			'post_status'    => 'publish',
			'post_type'      => 'player',
			'posts_per_page' => $params['number_of_items'],
			'orderby'        => $params['orderby'],
			'order'          => $params['order']
		);
		
		if ( ! empty( $params['category'] ) ) {
			$query_array['player-category'] = $params['category'];
		}
		
		$player_ids = null;
		if ( ! empty( $params['selected_players'] ) ) {
			$player_ids              = explode( ',', $params['selected_players'] );
            $query_array['orderby'] = 'post__in';
			$query_array['post__in'] = $player_ids;
		}
		
		return $query_array;
	}
	
	/**
	 * Generates player holder classes
	 *
	 * @param $params
	 *
	 * @return string
	 */
	public function getHolderClasses( $params, $args ) {
		$classes = array();
		
		$classes[] = ! empty( $params['number_of_columns'] ) ? 'edgtf-' . $params['number_of_columns'] . '-columns' : 'edgtf-' . $args['number_of_columns'] . '-columns';
		$classes[] = ! empty( $params['space_between_items'] ) ? 'edgtf-' . $params['space_between_items'] . '-space' : 'edgtf-' . $args['space_between_items'] . '-space';
		
		return implode( ' ', $classes );
	}
	
	/**
	 * Generates player inner classes
	 *
	 * @param $params
	 *
	 * @return string
	 */
	public function getInnerClasses( $params ) {
		$classes = array();
		
		if ( $params['player_slider'] === 'yes' ) {
			$classes[] = 'edgtf-owl-slider edgtf-list-is-slider';
		}
		
		return implode( ' ', $classes );
	}
	
	/**
	 * Return player Slider data attribute
	 *
	 * @param $params
	 *
	 * @return array
	 */
	
	private function getDataAttribute( $params, $args ) {
		$data_attrs = array();

		$data_attrs['data-number-of-columns'] = ! empty( $params['number_of_columns'] ) ? $params['number_of_columns'] : $args['number_of_columns'];
		$data_attrs['data-enable-navigation'] = ! empty( $params['slider_navigation'] ) ? $params['slider_navigation'] : '';
		$data_attrs['data-enable-pagination'] = ! empty( $params['slider_pagination'] ) ? $params['slider_pagination'] : '';
		$data_attrs['data-slider-margin'] = 'no';

		return $data_attrs;
	}

	public function getPlayerSocialIcons($id) {
		$social_icons = array();

		for($i = 1; $i < 6; $i++) {
			$player_icon_pack = get_post_meta($id, 'edgtf_player_social_icon_pack_'.$i, true);
			if($player_icon_pack) {
				$player_icon_collection = overworld_edge_icon_collections()->getIconCollection(get_post_meta($id, 'edgtf_player_social_icon_pack_' . $i, true));
				$player_social_icon = get_post_meta($id, 'edgtf_player_social_icon_pack_' . $i . '_' . $player_icon_collection->param, true);
				$player_social_link = get_post_meta($id, 'edgtf_player_social_icon_' . $i . '_link', true);
				$player_social_target = get_post_meta($id, 'edgtf_player_social_icon_' . $i . '_target', true);

				if ($player_social_icon !== '') {

					$player_icon_params = array();
					$player_icon_params['icon_pack'] = $player_icon_pack;
					$player_icon_params[$player_icon_collection->param] = $player_social_icon;
					$player_icon_params['link'] = ($player_social_link !== '') ? $player_social_link : '';
					$player_icon_params['target'] = ($player_social_target !== '') ? $player_social_target : '';

					$social_icons[] = overworld_edge_execute_shortcode('edgtf_icon', $player_icon_params);
				}
			}
		}

		return $social_icons;
	}

	/**
	 * Filter player categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function playerCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS player_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'player-category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['player_category_title'] ) > 0 ) ? esc_html__( 'Category', 'overworld-core' ) . ': ' . $value['player_category_title'] : '' );
				$results[]     = $data;
			}
		}

		return $results;
	}

	/**
	 * Find player category by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function playerCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get player category
			$player_category = get_term_by( 'slug', $query, 'player-category' );
			if ( is_object( $player_category ) ) {

				$player_category_slug = $player_category->slug;
				$player_category_title = $player_category->name;

				$player_category_title_display = '';
				if ( ! empty( $player_category_title ) ) {
					$player_category_title_display = esc_html__( 'Category', 'overworld-core' ) . ': ' . $player_category_title;
				}

				$data          = array();
				$data['value'] = $player_category_slug;
				$data['label'] = $player_category_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}

	/**
	 * Filter players by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function playerIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$player_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts} 
					WHERE post_type = 'player' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $player_id > 0 ? $player_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $value['id'];
				$data['label'] = esc_html__( 'Id', 'overworld-core' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'overworld-core' ) . ': ' . $value['title'] : '' );
				$results[] = $data;
			}
		}

		return $results;
	}

	/**
	 * Find player by id
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function playerIdAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get player
			$player = get_post( (int) $query );
			if ( ! is_wp_error( $player ) ) {

				$player_id = $player->ID;
				$player_title = $player->post_title;

				$player_title_display = '';
				if ( ! empty( $player_title ) ) {
					$player_title_display = ' - ' . esc_html__( 'Title', 'overworld-core' ) . ': ' . $player_title;
				}

				$player_id_display = esc_html__( 'Id', 'overworld-core' ) . ': ' . $player_id;

				$data          = array();
				$data['value'] = $player_id;
				$data['label'] = $player_id_display . $player_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}