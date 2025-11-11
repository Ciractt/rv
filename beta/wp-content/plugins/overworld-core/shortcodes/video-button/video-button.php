<?php

namespace OverworldCore\CPT\Shortcodes\VideoButton;

use OverworldCore\Lib;

class VideoButton implements Lib\ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'edgtf_video_button';
		
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'                      => esc_html__( 'Video Button', 'overworld-core' ),
					'base'                      => $this->getBase(),
					'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
					'icon'                      => 'icon-wpb-video-button extended-custom-icon',
					'allowed_container_element' => 'vc_row',
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'custom_class',
							'heading'     => esc_html__( 'Custom CSS Class', 'overworld-core' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'video_link',
							'heading'    => esc_html__( 'Video Link', 'overworld-core' )
						),
						array(
							'type'        => 'attach_image',
							'param_name'  => 'video_image',
							'heading'     => esc_html__( 'Video Image', 'overworld-core' ),
							'description' => esc_html__( 'Select image from media library', 'overworld-core' )
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'play_button_color',
							'heading'    => esc_html__( 'Play Button Color', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'play_button_size',
							'heading'    => esc_html__( 'Play Button Size (px)', 'overworld-core' )
						),
						array(
							'type'        => 'attach_image',
							'param_name'  => 'play_button_image',
							'heading'     => esc_html__( 'Play Button Custom Image', 'overworld-core' ),
							'description' => esc_html__( 'Select image from media library. If you use this field then play button color and button size options will not work', 'overworld-core' )
						),
						array(
							'type'        => 'attach_image',
							'param_name'  => 'play_button_hover_image',
							'heading'     => esc_html__( 'Play Button Custom Hover Image', 'overworld-core' ),
							'description' => esc_html__( 'Select image from media library. If you use this field then play button color and button size options will not work', 'overworld-core' )
						)
					)
				)
			);
		}
	}
	
	public function render( $atts, $content = null ) {
		$args   = array(
			'custom_class'            => '',
			'video_link'              => '#',
			'video_image'             => '',
			'play_button_color'       => '',
			'play_button_size'        => '',
			'play_button_image'       => '',
			'play_button_hover_image' => ''
		);
		$params = shortcode_atts( $args, $atts );

		$params['holder_classes']           = $this->getHolderClasses( $params );
		$params['play_button_styles']       = $this->getPlayButtonStyles( $params );
		$params['play_button_inner_styles'] = $this->getPlayButtonInnerStyles( $params );

		$html = overworld_core_get_shortcode_module_template_part( 'templates/video-button', 'video-button', '', $params );
		
		return $html;
	}
	
	private function getHolderClasses( $params ) {
		$holderClasses = array();
		
		$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
		$holderClasses[] = ! empty( $params['video_image'] ) ? 'edgtf-vb-has-img' : '';
		
		return implode( ' ', $holderClasses );
	}
	
	private function getPlayButtonStyles( $params ) {
		$styles = array();
		
		if ( ! empty( $params['play_button_color'] ) ) {
			$styles[] = 'color: ' . $params['play_button_color'];
		}

		if ( ! empty( $params['play_button_size'] ) ) {
			$styles[] = 'width: ' . overworld_edge_filter_px( $params['play_button_size'] ) . 'px';
			$styles[] = 'height: ' . overworld_edge_filter_px( $params['play_button_size'] ) . 'px';
			$styles[] = 'font-size: calc(0.85*' . overworld_edge_filter_px( $params['play_button_size'] ) . 'px)';
		}
		
		return implode( ';', $styles );
	}

	private function getPlayButtonInnerStyles( $params ) {
		$styles = array();

		if ( ! empty( $params['play_button_size'] ) ) {
			$styles[] = 'left: calc(0.06*' . overworld_edge_filter_px( $params['play_button_size'] ) . 'px)';
		}

		return implode( ';', $styles );
	}
}