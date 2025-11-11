<?php
namespace OverworldCore\CPT\Shortcodes\Match;

use OverworldCore\Lib;

class MatchListSimple implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_match_list_simple';

        add_action('vc_before_init', array($this, 'vcMap'));

	    //Match category filter
	    add_filter( 'vc_autocomplete_edgtf_match_list_simple_category_callback', array( &$this, 'matchCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Match category render
	    add_filter( 'vc_autocomplete_edgtf_match_list_simple_category_render', array( &$this, 'matchCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Match selected projects filter
	    add_filter( 'vc_autocomplete_edgtf_match_list_simple_selected_matches_callback', array( &$this, 'matchIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Match selected projects render
	    add_filter( 'vc_autocomplete_edgtf_match_list_simple_selected_matches_render', array( &$this, 'matchIdAutocompleteRender', ), 10, 1 ); // Render exact match. Must return an array (label,value)
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
				    'name'                      => esc_html__( 'Match List Simple', 'overworld-core' ),
				    'base'                      => $this->getBase(),
				    'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
				    'icon'                      => 'icon-wpb-match-list-simple extended-custom-icon',
				    'allowed_container_element' => 'vc_row',
				    'params'                    => array(
					    array(
						    'type'        => 'textfield',
						    'param_name'  => 'number_of_items',
						    'heading'     => esc_html__( 'Number of matches per page', 'overworld-core' ),
						    'description' => esc_html__( 'Set number of items for your match list. Enter -1 to show all.', 'overworld-core' ),
						    'value'       => '-1'
					    ),
					    array(
						    'type'        => 'autocomplete',
						    'param_name'  => 'category',
						    'heading'     => esc_html__( 'One-Category Match List', 'overworld-core' ),
						    'description' => esc_html__( 'Enter one category slug (leave empty for showing all categories)', 'overworld-core' )
					    ),
					    array(
						    'type'        => 'autocomplete',
						    'param_name'  => 'selected_matches',
						    'heading'     => esc_html__( 'Show Only Matches with Listed IDs', 'overworld-core' ),
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
			'number_of_items'     => '-1',
			'category'            => '',
			'selected_matches'    => '',
			'tag'                 => '',
			'orderby'             => 'date',
			'order'               => 'ASC',
			'match_slider'        => 'no',
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
		
		$params['this_object'] = $this;

		$html = overworld_core_get_cpt_shortcode_module_template_part( 'match', 'match-list-simple', 'match-holder', '', $params, $additional_params );
		
		return $html;
	}
	
	/**
	 * Generates match list query attribute array
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public function getQueryArray( $params ) {
		$query_array = array(
			'post_status'    => 'publish',
			'post_type'      => 'match',
			'posts_per_page' => $params['number_of_items'],
			'orderby'        => $params['orderby'],
			'order'          => $params['order']
		);
		
		if ( ! empty( $params['category'] ) ) {
			$query_array['match-category'] = $params['category'];
		}
		
		$match_ids = null;
		if ( ! empty( $params['selected_matches'] ) ) {
			$match_ids              = explode( ',', $params['selected_matches'] );
            $query_array['orderby'] = 'post__in';
			$query_array['post__in'] = $match_ids;
		}
		
		return $query_array;
	}

	/**
	 * Filter match categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function matchCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS match_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'match-category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['match_category_title'] ) > 0 ) ? esc_html__( 'Category', 'overworld-core' ) . ': ' . $value['match_category_title'] : '' );
				$results[]     = $data;
			}
		}

		return $results;
	}

	/**
	 * Find match category by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function matchCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get match category
			$match_category = get_term_by( 'slug', $query, 'match-category' );
			if ( is_object( $match_category ) ) {

				$match_category_slug = $match_category->slug;
				$match_category_title = $match_category->name;

				$match_category_title_display = '';
				if ( ! empty( $match_category_title ) ) {
					$match_category_title_display = esc_html__( 'Category', 'overworld-core' ) . ': ' . $match_category_title;
				}

				$data          = array();
				$data['value'] = $match_category_slug;
				$data['label'] = $match_category_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}

	/**
	 * Filter matches by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function matchIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$match_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts} 
					WHERE post_type = 'match' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $match_id > 0 ? $match_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

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
	 * Find match by id
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function matchIdAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get match
			$match = get_post( (int) $query );
			if ( ! is_wp_error( $match ) ) {

				$match_id = $match->ID;
				$match_title = $match->post_title;

				$match_title_display = '';
				if ( ! empty( $match_title ) ) {
					$match_title_display = ' - ' . esc_html__( 'Title', 'overworld-core' ) . ': ' . $match_title;
				}

				$match_id_display = esc_html__( 'Id', 'overworld-core' ) . ': ' . $match_id;

				$data          = array();
				$data['value'] = $match_id;
				$data['label'] = $match_id_display . $match_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}