<?php

namespace OverworldCore\CPT\Shortcodes\StreamList;

use OverworldCore\lib;

class StreamList implements lib\ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'edgtf_stream_list';
		
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'                      => esc_html__( 'Stream List', 'overworld-core' ),
					'base'                      => $this->base,
					'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
					'icon'                      => 'icon-wpb-stream-list extended-custom-icon',
					'allowed_container_element' => 'vc_row',
					'params'                    => array(
						array(
							'type'        => 'dropdown',
							'param_name'  => 'number_of_columns',
							'heading'     => esc_html__( 'Number of Columns', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_number_of_columns_array( true, array( 'five', 'six' ) ) ),
							'save_always' => true
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'space_between_items',
							'heading'     => esc_html__( 'Space Between Items', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_space_between_items_array() ),
							'save_always' => true
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'title_tag',
							'heading'    => esc_html__( 'Stream Title Tag', 'overworld-core' ),
							'value'      => array_flip( overworld_edge_get_title_tag( true ) )
						),
						array(
							'type'       => 'param_group',
							'heading'    => esc_html__( 'Additional Stream Items', 'overworld-core' ),
							'param_name' => 'stream_items',
							'params'     => array(
								array(
									'type'       => 'attach_image',
									'param_name' => 'stream_background_image',
									'heading'    => esc_html__( 'Stream Background Image', 'overworld-core' ),
								),
								array(
									'type'        => 'textfield',
									'param_name'  => 'stream_title',
									'heading'     => esc_html__( 'Stream Title', 'overworld-core' ),
									'admin_label' => true
								),
								array(
									'type'        => 'textfield',
									'param_name'  => 'stream_platform',
									'heading'     => esc_html__( ' Streaming video platform ', 'overworld-core' ),
									'admin_label' => true
								),
								array(
									'type'        => 'textfield',
									'param_name'  => 'stream_channel',
									'heading'     => esc_html__( ' Streaming video platform channel name', 'overworld-core' ),
									'admin_label' => true
								),
								array(
									'type'        => 'textfield',
									'param_name'  => 'stream_link',
									'heading'     => esc_html__( 'Stream link', 'overworld-core' ),
									'admin_label' => true
								),
								array(
									'type'       => 'colorpicker',
									'param_name' => 'stream_link_color',
									'heading'    => esc_html__( 'Stream link Color', 'overworld-core' )
								)
							)
						),
					)
				)
			);
		}
	}
	
	public function render( $atts, $content = null ) {
		$args   = array(
			'number_of_columns'   => 'three',
			'space_between_items' => 'normal',
			'title_tag'           => 'h3',
			'stream_items'        => '',
		);
		$params = shortcode_atts( $args, $atts );
		
		$params['title_tag']      = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
		$params['holder_classes'] = $this->getHolderClasses( $params, $args );
		$params['stream_items']   = json_decode( urldecode( $params['stream_items'] ), true );
		$params['this_object']    = $this;
		$html                     = overworld_core_get_shortcode_module_template_part( 'templates/stream-list', 'stream-list', '', $params );
		
		return $html;
	}
	
	private function getHolderClasses( $params, $args ) {
		$holderClasses   = array();
		$holderClasses[] = ! empty( $params['number_of_columns'] ) ? 'edgtf-' . $params['number_of_columns'] . '-columns' : 'edgtf-' . $args['number_of_columns'] . '-columns';
		$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'edgtf-' . $params['space_between_items'] . '-space' : 'edgtf-' . $args['space_between_items'] . '-space';
		
		return implode( ' ', $holderClasses );
	}

	public function getStreamLinkStyles( $params ) {
		$styles = array();

		if ( ! empty( $params['stream_link_color'] ) ) {
			$styles[] = 'background-color: ' . $params['stream_link_color'];
		}

		return implode( ';', $styles );
	}
}