<?php
namespace OverworldCore\CPT\Shortcodes\Tournament;

use OverworldCore\Lib;

class TournamentTimetable implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edgtf_tournament_timetable';

        add_action('vc_before_init', array($this, 'vcMap'));
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
			        'name'                      => esc_html__( 'Tournament Timetable', 'overworld-core' ),
			        'base'                      => $this->getBase(),
			        'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
			        'icon'                      => 'icon-wpb-tournament-timetable extended-custom-icon',
			        'allowed_container_element' => 'vc_row',
			        'params'                    => array(
                        array(
							'type'       => 'param_group',
							'param_name' => 'timetable_items',
							'heading'    => esc_html__( 'Timetable Items', 'overworld-core' ),
							'params'     => array(
								array(
									'type'        => 'dropdown',
									'param_name'  => 'day',
									'heading'     => esc_html__( 'Day', 'overworld-core' ),
									'value'       => array(
										esc_html__( 'Monday', 'overworld-core' )    => 'monday',
										esc_html__( 'Tuesday', 'overworld-core' )   => 'tuesday',
										esc_html__( 'Wednesday', 'overworld-core' ) => 'wednesday',
										esc_html__( 'Thursday', 'overworld-core' )  => 'thursday',
										esc_html__( 'Friday', 'overworld-core' )    => 'friday',
										esc_html__( 'Saturday', 'overworld-core' )  => 'saturday',
										esc_html__( 'Sunday', 'overworld-core' )    => 'sunday'
									)
								),
								array(
									'type'       => 'textfield',
									'param_name' => 'message',
									'heading'    => esc_html__( 'Message', 'overworld-core' ),
								),
								array(
									'type'       => 'textfield',
									'param_name' => 'event_title',
									'heading'    => esc_html__( 'Event Title', 'overworld-core' ),
								),
								array(
									'type'       => 'textfield',
									'param_name' => 'link',
									'heading'    => esc_html__( 'Link', 'overworld-core' )
								),
								array(
									'type'        => 'dropdown',
									'param_name'  => 'link_target',
									'heading'     => esc_html__( 'Link Target', 'overworld-core' ),
									'value'       => array_flip( overworld_edge_get_link_target_array() )
								),
							)
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
	        'timetable_items' => ''
        );

		$params = shortcode_atts($args, $atts);

	    $params['timetable_items'] = json_decode( urldecode( $params['timetable_items'] ), true );

        $html = overworld_core_get_cpt_shortcode_module_template_part('tournament', 'tournament-timetable', 'timetable', '', $params);

        return $html;
	}
}