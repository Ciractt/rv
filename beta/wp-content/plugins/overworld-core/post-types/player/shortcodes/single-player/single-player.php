<?php
namespace OverworldCore\CPT\Shortcodes\Player;

use OverworldCore\Lib;

class SinglePlayer implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_single_player';

        add_action('vc_before_init', array($this, 'vcMap'));

	    //Player project id filter
	    add_filter( 'vc_autocomplete_edgtf_single_player_player_id_callback', array( &$this, 'playerIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

	    //Player project id render
	    add_filter( 'vc_autocomplete_edgtf_single_player_player_id_render', array( &$this, 'playerIdAutocompleteRender', ), 10, 1 ); // Render exact portfolio. Must return an array (label,value)
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
			        'name'                      => esc_html__( 'Single Player', 'overworld-core' ),
			        'base'                      => $this->getBase(),
			        'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
			        'icon'                      => 'icon-wpb-single-player extended-custom-icon',
			        'allowed_container_element' => 'vc_row',
			        'params'                    => array(
                        array(
					        'type'       => 'autocomplete',
					        'param_name' => 'player_id',
					        'heading'    => esc_html__( 'Select Player', 'overworld-core' ),
					        'settings'   => array(
						        'sortable'      => true,
						        'unique_values' => true
					        ),
					        'description' => esc_html__( 'If you left this field empty then player ID will be of the current page', 'overworld-core' )
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
	        'player_id' => 'title'
        );

		$params = shortcode_atts($args, $atts);
		extract($params);

	    $params['player_id']           = ! empty( $params['player_id'] ) ? $params['player_id'] : get_the_ID();
	    $params['nickname']            = get_post_meta( $params['player_id'], 'edgtf_player_nickname', true );
	    $params['birth_date']          = get_post_meta( $params['player_id'], 'edgtf_player_birth_date', true );
	    $params['team']                = get_post_meta( $params['player_id'], 'edgtf_player_team', true );
	    $params['role']                = get_post_meta( $params['player_id'], 'edgtf_player_role', true );
	    $params['nationality']         = get_post_meta( $params['player_id'], 'edgtf_player_nationality', true );
	    $params['nationality_flag']    = get_post_meta( $params['player_id'], 'edgtf_player_nationality_flag', true );
	    $params['player_social_icons'] = $this->getPlayerSocialIcons( $params['player_id'] );

        $html = overworld_core_get_cpt_shortcode_module_template_part('player', 'single-player', 'player', '', $params);

        return $html;
	}

    private function getPlayerSocialIcons($id) {
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
	 * Filter player by ID or Title
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function playerIdAutocompleteSuggester( $query ) {
		global $wpdb;
		$portfolio_id = (int) $query;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts} 
					WHERE post_type = 'player' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $portfolio_id > 0 ? $portfolio_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

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
			// get portfolio
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