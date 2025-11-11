<?php
namespace OverworldCore\CPT\Shortcodes\Match;

use OverworldCore\Lib;

class SingleMatchSimple implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_single_match_simple';

        add_action('vc_before_init', array($this, 'vcMap'));

	    //Match project id filter
	    add_filter( 'vc_autocomplete_edgtf_single_match_simple_match_id_callback', array( &$this, 'matchIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Match project id render
	    add_filter( 'vc_autocomplete_edgtf_single_match_simple_match_id_render', array( &$this, 'matchIdAutocompleteRender', ), 10, 1 ); // Render exact portfolio. Must return an array (label,value)
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
	        vc_map( array(
			        'name'                      => esc_html__( 'Single Match Simple', 'overworld-core' ),
			        'base'                      => $this->getBase(),
			        'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
			        'icon'                      => 'icon-wpb-single-match-simple extended-custom-icon',
			        'allowed_container_element' => 'vc_row',
			        'params'                    => array(
                        array(
					        'type'       => 'autocomplete',
					        'param_name' => 'match_id',
					        'heading'    => esc_html__( 'Select Match', 'overworld-core' ),
					        'settings'   => array(
						        'sortable'      => true,
						        'unique_values' => true
					        ),
					        'description' => esc_html__( 'If you left this field empty then match ID will be of the current page', 'overworld-core' )
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
     * @return string
     */
    public function render($atts, $content = null) {
        $args = array(
	        'match_id'  => 'title'
        );

		$params = shortcode_atts($args, $atts);

	    $params['match_id']     = ! empty( $params['match_id'] ) ? $params['match_id'] : get_the_ID();
	    $params['status']       = get_post_meta( $params['match_id'], 'edgtf_match_status_meta', true );
	    $params['tournament']   = get_post_meta( $params['match_id'], 'edgtf_match_tournament', true );
	    $params['team_1']       = get_post_meta( $params['match_id'], 'edgtf_match_team_1', true );
	    $params['team_1_score'] = get_post_meta( $params['match_id'], 'edgtf_match_team_1_score_meta', true );
	    $params['team_2']       = get_post_meta( $params['match_id'], 'edgtf_match_team_2', true );
	    $params['team_2_score'] = get_post_meta( $params['match_id'], 'edgtf_match_team_2_score_meta', true );
	    $params['date']         = get_post_meta( $params['match_id'], 'edgtf_match_date_meta', true );
	    $params['time']         = get_post_meta( $params['match_id'], 'edgtf_match_time_meta', true );

        $html = overworld_core_get_cpt_shortcode_module_template_part('match', 'single-match-simple', 'match', '', $params);

        return $html;
	}

	/**
	 * Filter match by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function matchIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$portfolio_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts} 
					WHERE post_type = 'match' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $portfolio_id > 0 ? $portfolio_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

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
			// get portfolio
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