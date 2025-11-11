<?php
namespace OverworldCore\CPT\Shortcodes\Player;

use OverworldCore\Lib;

class PlayerSlider implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_player_slider';

        add_action('vc_before_init', array($this, 'vcMap'));

        //Player category filter
        add_filter( 'vc_autocomplete_edgtf_player_slider_category_callback', array( &$this, 'playerCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Player category render
        add_filter( 'vc_autocomplete_edgtf_player_slider_category_render', array( &$this, 'playerCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Player selected projects filter
        add_filter( 'vc_autocomplete_edgtf_player_slider_selected_players_callback', array( &$this, 'playerIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Player selected projects render
        add_filter( 'vc_autocomplete_edgtf_player_slider_selected_players_render', array( &$this, 'playerIdAutocompleteRender', ), 10, 1 ); // Render exact player. Must return an array (label,value)
    }

    public function getBase() {
        return $this->base;
    }

    public function vcMap() {
	    if(function_exists('vc_map')) {
		    vc_map(
		    	array(
				    'name'                      => esc_html__( 'Player Slider', 'overworld-core' ),
				    'base'                      => $this->base,
				    'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
				    'icon'                      => 'icon-wpb-player-slider extended-custom-icon',
				    'allowed_container_element' => 'vc_row',
				    'params'                    => array(
					    array(
						    'type'        => 'dropdown',
						    'param_name'  => 'number_of_columns',
						    'heading'     => esc_html__( 'Number of Columns in Row', 'overworld-core' ),
						    'value'       => array_flip( overworld_edge_get_number_of_columns_array( false ) ),
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
						    'heading'     => esc_html__( 'Order By', 'overworld-core' ),
						    'value'       => array_flip( overworld_edge_get_query_order_by_array() ),
						    'save_always' => true
					    ),
					    array(
						    'type'        => 'dropdown',
						    'param_name'  => 'order',
						    'heading'     => esc_html__( 'Order', 'overworld-core' ),
						    'value'       => array_flip( overworld_edge_get_query_order_array() ),
						    'save_always' => true
					    ),
					    array(
						    'type'        => 'dropdown',
						    'param_name'  => 'slider_navigation',
						    'heading'     => esc_html__( 'Enable Slider Navigation Arrows', 'overworld-core' ),
						    'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						    'save_always' => true
					    ),
					    array(
						    'type'        => 'dropdown',
						    'param_name'  => 'slider_pagination',
						    'heading'     => esc_html__( 'Enable Slider Pagination', 'overworld-core' ),
						    'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						    'save_always' => true
					    )
				    )
			    )
		    );
	    }
    }

    public function render($atts, $content = null) {
        $default_atts = array(
	        'number_of_columns'   => 'three',
	        'number_of_items'     => '-1',
	        'category'            => '',
	        'selected_players'    => '',
	        'tag'                 => '',
	        'orderby'             => 'date',
	        'order'               => 'ASC',
	        'player_slider'       => 'yes',
	        'slider_navigation'   => 'yes',
	        'slider_pagination'   => 'yes'
        );

        $params = shortcode_atts($default_atts, $atts);

        $params['content'] = $content;

        $html = '';
        $html .= '<div class="edgtf-player-slider-holder">';
        $html .= overworld_edge_execute_shortcode('edgtf_player_list', $params);
        $html .= '</div>';

        return $html;
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