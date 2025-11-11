<?php
namespace OverworldCore\CPT\Shortcodes\Team;

use OverworldCore\Lib;

class SingleTeam implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_single_team';

        add_action('vc_before_init', array($this, 'vcMap'));

	    //Team project id filter
	    add_filter( 'vc_autocomplete_edgtf_single_team_team_id_callback', array( &$this, 'teamIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Team project id render
	    add_filter( 'vc_autocomplete_edgtf_single_team_team_id_render', array( &$this, 'teamIdAutocompleteRender', ), 10, 1 ); // Render exact portfolio. Must return an array (label,value)
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
			        'name'                      => esc_html__( 'Single Team', 'overworld-core' ),
			        'base'                      => $this->getBase(),
			        'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
			        'icon'                      => 'icon-wpb-single-team extended-custom-icon',
			        'allowed_container_element' => 'vc_row',
			        'params'                    => array(
                        array(
					        'type'       => 'autocomplete',
					        'param_name' => 'team_id',
					        'heading'    => esc_html__( 'Select Team', 'overworld-core' ),
					        'settings'   => array(
						        'sortable'      => true,
						        'unique_values' => true
					        ),
					        'description' => esc_html__( 'If you left this field empty then team ID will be of the current page', 'overworld-core' )
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
	        'team_id' => 'title'
        );

		$params = shortcode_atts($args, $atts);
		extract($params);

	    $params['team_id']           = ! empty( $params['team_id'] ) ? $params['team_id'] : get_the_ID();
	    $params['sponsor']           = get_post_meta( $params['team_id'], 'edgtf_team_sponsor', true );
	    $params['sponsor_logo']      = get_post_meta( $params['team_id'], 'edgtf_team_sponsor_logo', true );
	    $params['team_social_icons'] = $this->getTeamSocialIcons( $params['team_id'] );

        $html = overworld_core_get_cpt_shortcode_module_template_part('team', 'single-team', 'team', '', $params);

        return $html;
	}

    private function getTeamSocialIcons($id) {
        $social_icons = array();

        for($i = 1; $i < 6; $i++) {
            $team_icon_pack = get_post_meta($id, 'edgtf_team_social_icon_pack_'.$i, true);
            if($team_icon_pack) {
                $team_icon_collection = overworld_edge_icon_collections()->getIconCollection(get_post_meta($id, 'edgtf_team_social_icon_pack_' . $i, true));
                $team_social_icon = get_post_meta($id, 'edgtf_team_social_icon_pack_' . $i . '_' . $team_icon_collection->param, true);
                $team_social_link = get_post_meta($id, 'edgtf_team_social_icon_' . $i . '_link', true);
                $team_social_target = get_post_meta($id, 'edgtf_team_social_icon_' . $i . '_target', true);

                if ($team_social_icon !== '') {

                    $team_icon_params = array();
                    $team_icon_params['icon_pack'] = $team_icon_pack;
                    $team_icon_params[$team_icon_collection->param] = $team_social_icon;
                    $team_icon_params['link'] = ($team_social_link !== '') ? $team_social_link : '';
                    $team_icon_params['target'] = ($team_social_target !== '') ? $team_social_target : '';

                    $social_icons[] = overworld_edge_execute_shortcode('edgtf_icon', $team_icon_params);
                }
            }
        }

        return $social_icons;
    }

	/**
	 * Filter team by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function teamIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$portfolio_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts} 
					WHERE post_type = 'team' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $portfolio_id > 0 ? $portfolio_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

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
	 * Find team by id
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function teamIdAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get portfolio
			$team = get_post( (int) $query );
			if ( ! is_wp_error( $team ) ) {

				$team_id = $team->ID;
                $team_title = $team->post_title;

                $team_title_display = '';
				if ( ! empty( $team_title ) ) {
                    $team_title_display = ' - ' . esc_html__( 'Title', 'overworld-core' ) . ': ' . $team_title;
				}

                $team_id_display = esc_html__( 'Id', 'overworld-core' ) . ': ' . $team_id;

				$data          = array();
				$data['value'] = $team_id;
				$data['label'] = $team_id_display . $team_title_display;

				return ! empty( $data ) ? $data : false;
			}

			return false;
		}

		return false;
	}
}