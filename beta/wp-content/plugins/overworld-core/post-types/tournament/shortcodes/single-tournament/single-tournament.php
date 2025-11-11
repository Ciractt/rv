<?php
namespace OverworldCore\CPT\Shortcodes\Tournament;

use OverworldCore\Lib;

class SingleTournament implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_single_tournament';

        add_action('vc_before_init', array($this, 'vcMap'));

	    //Tournament project id filter
	    add_filter( 'vc_autocomplete_edgtf_single_tournament_tournament_id_callback', array( &$this, 'tournamentIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Tournament project id render
	    add_filter( 'vc_autocomplete_edgtf_single_tournament_tournament_id_render', array( &$this, 'tournamentIdAutocompleteRender', ), 10, 1 ); // Render exact portfolio. Must return an array (label,value)
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
			        'name'                      => esc_html__( 'Single Tournament', 'overworld-core' ),
			        'base'                      => $this->getBase(),
			        'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
			        'icon'                      => 'icon-wpb-single-tournament extended-custom-icon',
			        'allowed_container_element' => 'vc_row',
			        'params'                    => array(
                        array(
					        'type'       => 'autocomplete',
					        'param_name' => 'tournament_id',
					        'heading'    => esc_html__( 'Select Tournament', 'overworld-core' ),
					        'settings'   => array(
						        'sortable'      => true,
						        'unique_values' => true
					        ),
					        'description' => esc_html__( 'If you left this field empty then tournament ID will be of the current page', 'overworld-core' )
				        ),
                        array(
							'type'        => 'dropdown',
							'param_name'  => 'info_position',
							'heading'     => esc_html__( 'Tournament Info Position', 'overworld-core' ),
							'value'       => array(
								esc_html__( 'Info On Image', 'overworld-core' )    => 'info-on-image',
								esc_html__( 'Info Below Image', 'overworld-core' ) => 'info-below-image'
							),
							'save_always' => true
						),
                        array(
							'type'       => 'dropdown',
							'param_name' => 'title_tag',
							'heading'    => esc_html__( 'Title Tag', 'overworld-core' ),
							'value'      => array_flip( overworld_edge_get_title_tag( true ) )
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
	        'tournament_id' => 'title',
	        'info_position' => 'info-on-image',
	        'title_tag'     => 'h3'
        );

		$params = shortcode_atts($args, $atts);

	    $params['info_position']  = ! empty( $params['info_position'] ) ? $params['info_position'] : $args['info_position'];
	    $params['holder_classes'] = $this->getHolderClasses( $params, $args );
	    $params['title_tag']      = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];

	    $params['tournament_id']  = ! empty( $params['tournament_id'] ) ? $params['tournament_id'] : get_the_ID();
	    $params['status']         = get_post_meta( $params['tournament_id'], 'edgtf_tournament_status_meta', true );
	    $params['sponsor']        = get_post_meta( $params['tournament_id'], 'edgtf_tournament_sponsor', true );
	    $params['sponsor_logo']   = get_post_meta( $params['tournament_id'], 'edgtf_tournament_sponsor_logo', true );
	    $params['location']       = get_post_meta( $params['tournament_id'], 'edgtf_tournament_location_meta', true );
	    $params['date']           = get_post_meta( $params['tournament_id'], 'edgtf_tournament_date_meta', true );
	    $params['time']           = get_post_meta( $params['tournament_id'], 'edgtf_tournament_time_meta', true );
	    $params['stream_link']    = get_post_meta( $params['tournament_id'], 'edgtf_tournament_stream_link', true );
	    $params['prize_pool']     = get_post_meta( $params['tournament_id'], 'edgtf_tournament_prize_pool_meta', true );
	    $params['play_mode']      = get_post_meta( $params['tournament_id'], 'edgtf_tournament_play_mode_meta', true );
	    $params['platform']       = get_post_meta( $params['tournament_id'], 'edgtf_tournament_platform_meta', true );
	    $params['platform_logo']  = get_post_meta( $params['tournament_id'], 'edgtf_tournament_platform_logo_meta', true );
	    $params['players_number'] = get_post_meta( $params['tournament_id'], 'edgtf_tournament_players_number_meta', true );
	    $params['bg_image']       = get_post_meta( $params['tournament_id'], 'edgtf_tournament_bg_image_shortcode_meta', true );

        $html = overworld_core_get_cpt_shortcode_module_template_part('tournament', 'single-tournament', 'tournament', '', $params);

        return $html;
	}

	private function getHolderClasses( $params, $args ) {
		$holderClasses   = array();
		$holderClasses[] = ! empty( $params['info_position'] ) ? 'edgtf-' . $params['info_position'] : 'edgtf-' . $args['info_position'];

		return implode( ' ', $holderClasses );
	}

	/**
	 * Filter tournament by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function tournamentIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$portfolio_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts} 
					WHERE post_type = 'tournament' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $portfolio_id > 0 ? $portfolio_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

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
			// get portfolio
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