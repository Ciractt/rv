<?php
namespace OverworldCore\CPT\Shortcodes\Tournament;

use OverworldCore\Lib;

class TournamentSlider implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_tournament_slider';

        add_action('vc_before_init', array($this, 'vcMap'));

        //Tournament category filter
        add_filter( 'vc_autocomplete_edgtf_tournament_slider_category_callback', array( &$this, 'tournamentCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Tournament category render
        add_filter( 'vc_autocomplete_edgtf_tournament_slider_category_render', array( &$this, 'tournamentCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Tournament selected projects filter
        add_filter( 'vc_autocomplete_edgtf_tournament_slider_selected_tournaments_callback', array( &$this, 'tournamentIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Tournament selected projects render
        add_filter( 'vc_autocomplete_edgtf_tournament_slider_selected_tournaments_render', array( &$this, 'tournamentIdAutocompleteRender', ), 10, 1 ); // Render exact tournament. Must return an array (label,value)
    }

    public function getBase() {
        return $this->base;
    }

    public function vcMap() {
	    if(function_exists('vc_map')) {
		    vc_map(
		    	array(
				    'name'                      => esc_html__( 'Tournament Slider', 'overworld-core' ),
				    'base'                      => $this->base,
				    'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
				    'icon'                      => 'icon-wpb-tournament-slider extended-custom-icon',
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
						    'heading'     => esc_html__( 'Number of Tournaments per page', 'overworld-core' ),
						    'description' => esc_html__( 'Set number of items for your tournament list. Enter -1 to show all.', 'overworld-core' ),
						    'value'       => '-1'
					    ),
					    array(
						    'type'        => 'autocomplete',
						    'param_name'  => 'category',
						    'heading'     => esc_html__( 'One-Category Tournament List', 'overworld-core' ),
						    'description' => esc_html__( 'Enter one category slug (leave empty for showing all categories)', 'overworld-core' )
					    ),
					    array(
						    'type'        => 'autocomplete',
						    'param_name'  => 'selected_tournaments',
						    'heading'     => esc_html__( 'Show Only Tournaments with Listed IDs', 'overworld-core' ),
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
					    ),
					    array(
							'type'        => 'dropdown',
							'param_name'  => 'info_position',
							'heading'     => esc_html__( 'Tournament Info Position', 'overworld-core' ),
							'value'       => array(
								esc_html__( 'Info On Image', 'overworld-core' )    => 'info-on-image',
								esc_html__( 'Info Below Image', 'overworld-core' ) => 'info-below-image'
							),
							'save_always' => true,
							'group'       => esc_html__( 'Info Style', 'overworld-core' )
						),
                        array(
							'type'       => 'dropdown',
							'param_name' => 'title_tag',
							'heading'    => esc_html__( 'Title Tag', 'overworld-core' ),
							'value'      => array_flip( overworld_edge_get_title_tag( true ) ),
							'group'      => esc_html__( 'Info Style', 'overworld-core' )
						)
				    )
			    )
		    );
	    }
    }

    public function render($atts, $content = null) {
        $default_atts = array(
	        'number_of_columns'    => 'three',
	        'number_of_items'      => '-1',
	        'category'             => '',
	        'selected_tournaments' => '',
	        'tag'                  => '',
	        'orderby'              => 'date',
	        'order'                => 'ASC',
	        'tournament_slider'    => 'yes',
	        'slider_navigation'    => 'yes',
	        'slider_pagination'    => 'yes',
	        'info_position'        => 'info-on-image',
	        'title_tag'            => 'h3'
        );

        $params = shortcode_atts($default_atts, $atts);

        $params['info_position']  = ! empty( $params['info_position'] ) ? $params['info_position'] : $default_atts['info_position'];
	    $params['title_tag']      = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $default_atts['title_tag'];

        $params['content'] = $content;

        $html = '';
        $html .= '<div class="edgtf-tournament-slider-holder">';
        $html .= overworld_edge_execute_shortcode('edgtf_tournament_list', $params);
        $html .= '</div>';

        return $html;
    }

    /**
     * Filter tournament categories
     *
     * @param $query
     *
     * @return array
     */
    public function tournamentCategoryAutocompleteSuggester( $query ) {
        global $wpdb;
        $post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS tournament_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'tournament-category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data          = array();
                $data['value'] = $value['slug'];
                $data['label'] = ( ( strlen( $value['tournament_category_title'] ) > 0 ) ? esc_html__( 'Category', 'overworld-core' ) . ': ' . $value['tournament_category_title'] : '' );
                $results[]     = $data;
            }
        }

        return $results;
    }

    /**
     * Find tournament category by slug
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function tournamentCategoryAutocompleteRender( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {
            // get tournament category
            $tournament_category = get_term_by( 'slug', $query, 'tournament-category' );
            if ( is_object( $tournament_category ) ) {

                $tournament_category_slug = $tournament_category->slug;
                $tournament_category_title = $tournament_category->name;

                $tournament_category_title_display = '';
                if ( ! empty( $tournament_category_title ) ) {
                    $tournament_category_title_display = esc_html__( 'Category', 'overworld-core' ) . ': ' . $tournament_category_title;
                }

                $data          = array();
                $data['value'] = $tournament_category_slug;
                $data['label'] = $tournament_category_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }

    /**
     * Filter tournaments by ID or Title
     *
     * @param $query
     *
     * @return array
     */
    public function tournamentIdAutocompleteSuggester( $query ) {
        global $wpdb;
        $tournament_id = (int) $query;
        $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts}
					WHERE post_type = 'tournament' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $tournament_id > 0 ? $tournament_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

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
     * Find tournament by id
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function tournamentIdAutocompleteRender( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {
            // get tournament
            $tournament = get_post( (int) $query );
            if ( ! is_wp_error( $tournament ) ) {

                $tournament_id = $tournament->ID;
                $tournament_title = $tournament->post_title;

                $tournament_title_display = '';
                if ( ! empty( $tournament_title ) ) {
                    $tournament_title_display = ' - ' . esc_html__( 'Title', 'overworld-core' ) . ': ' . $tournament_title;
                }

                $tournament_id_display = esc_html__( 'Id', 'overworld-core' ) . ': ' . $tournament_id;

                $data          = array();
                $data['value'] = $tournament_id;
                $data['label'] = $tournament_id_display . $tournament_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }
}