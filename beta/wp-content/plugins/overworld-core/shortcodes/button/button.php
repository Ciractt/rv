<?php

namespace OverworldCore\CPT\Shortcodes\Button;

use OverworldCore\Lib;

class Button implements Lib\ShortcodeInterface {
	private $base;

	public function __construct() {
		$this->base = 'edgtf_button';

		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}

	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		if ( function_exists( 'vc_map' ) ) {
			vc_map( array(
					'name'                      => esc_html__( 'Button', 'overworld-core' ),
					'base'                      => $this->base,
					'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
					'icon'                      => 'icon-wpb-button extended-custom-icon',
					'allowed_container_element' => 'vc_row',
					'params'                    => array_merge( array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'custom_class',
							'heading'     => esc_html__( 'Custom CSS Class', 'overworld-core' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'type',
							'heading'     => esc_html__( 'Type', 'overworld-core' ),
							'value'       => array(
								esc_html__( 'Solid', 'overworld-core' )     => 'solid',
								esc_html__( 'Outline', 'overworld-core' )   => 'outline',
								esc_html__( 'Simple', 'overworld-core' )    => 'simple'
							),
							'admin_label' => true
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'size',
							'heading'    => esc_html__( 'Size', 'overworld-core' ),
							'value'      => array(
								esc_html__( 'Default', 'overworld-core' ) => '',
								esc_html__( 'Small', 'overworld-core' )   => 'small',
								esc_html__( 'Medium', 'overworld-core' )  => 'medium',
								esc_html__( 'Large', 'overworld-core' )   => 'large',
								esc_html__( 'Huge', 'overworld-core' )    => 'huge'
							),
							'dependency' => array( 'element' => 'type', 'value' => array( 'solid', 'outline' ) )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'text',
							'heading'     => esc_html__( 'Text', 'overworld-core' ),
							'value'       => esc_html__( 'Button Text', 'overworld-core' ),
							'save_always' => true,
							'admin_label' => true
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'link',
							'heading'    => esc_html__( 'Link', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'target',
							'heading'     => esc_html__( 'Link Target', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_link_target_array() ),
							'save_always' => true
						)
					), overworld_edge_icon_collections()->getVCParamsArray( array( 'element' => 'type', 'value' => array( 'solid', 'outline', 'simple' ) ), '', true ), array(
							array(
								'type'        => 'dropdown',
								'param_name'  => 'icon_position',
								'heading'     => esc_html__( 'Icon position', 'overworld-core' ),
								'value'       => array(
									esc_html__( 'Default', 'overworld-core' )      => '',
									esc_html__( 'On Left Side', 'overworld-core' ) => 'left',
								),
								'description' => esc_html__( 'This option is valid if icon on simple button is enabled', 'overworld-core' ),
								'dependency'  => array( 'element' => 'type', 'value' => array( 'simple' ) )
							),
							array(
								'type'       => 'colorpicker',
								'param_name' => 'color',
								'heading'    => esc_html__( 'Color', 'overworld-core' ),
								'group'      => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'       => 'colorpicker',
								'param_name' => 'hover_color',
								'heading'    => esc_html__( 'Hover Color', 'overworld-core' ),
								'group'      => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'       => 'colorpicker',
								'param_name' => 'background_color',
								'heading'    => esc_html__( 'Background Color', 'overworld-core' ),
								'dependency' => array( 'element' => 'type', 'value' => array( 'solid' ) ),
								'group'      => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'       => 'colorpicker',
								'param_name' => 'hover_background_color',
								'heading'    => esc_html__( 'Hover Background Color', 'overworld-core' ),
								'dependency' => array( 'element' => 'type', 'value' => array( 'solid', 'outline' ) ),
								'group'      => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'       => 'textfield',
								'param_name' => 'font_size',
								'heading'    => esc_html__( 'Font Size (px)', 'overworld-core' ),
								'group'      => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'        => 'dropdown',
								'param_name'  => 'font_weight',
								'heading'     => esc_html__( 'Font Weight', 'overworld-core' ),
								'value'       => array_flip( overworld_edge_get_font_weight_array( true ) ),
								'save_always' => true,
								'group'       => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'        => 'dropdown',
								'param_name'  => 'text_transform',
								'heading'     => esc_html__( 'Text Transform', 'overworld-core' ),
								'value'       => array_flip( overworld_edge_get_text_transform_array( true ) ),
								'save_always' => true
							),
							array(
								'type'        => 'textfield',
								'param_name'  => 'margin',
								'heading'     => esc_html__( 'Margin', 'overworld-core' ),
								'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'overworld-core' ),
								'group'       => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'        => 'textfield',
								'param_name'  => 'padding',
								'heading'     => esc_html__( 'Button Padding', 'overworld-core' ),
								'description' => esc_html__( 'Insert padding in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'overworld-core' ),
								'dependency'  => array( 'element' => 'type', 'value' => array( 'solid', 'outline' ) ),
								'group'       => esc_html__( 'Design Options', 'overworld-core' )
							),
							array(
								'type'        => 'dropdown',
								'param_name'  => 'cutting_edge',
								'heading'     => esc_html__( 'Cutting Edge', 'overworld-core' ),
								'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
								'group'       => esc_html__( 'Design Options', 'overworld-core' ),
								'dependency'  => array( 'element' => 'type', 'value' => array( 'solid' ) ),
								'save_always' => true
							),
							array(
								'type'        => 'textfield',
								'param_name'  => 'cutting_edge_value',
								'heading'     => esc_html__( 'Cutting Edge Path', 'overworld-core' ),
								'description' => esc_html__( 'Insert path in format: polygon(0 0,90% 0,100% 32%,100% 100%,10% 100%,0 68%)', 'overworld-core' ),
								'dependency'  => array( 'element' => 'cutting_edge', 'value' => array( 'yes' ) ),
								'group'       => esc_html__( 'Design Options', 'overworld-core' )
							),
						) )
				) );
		}
	}

	public function render( $atts, $content = null ) {
		$default_atts = array(
			'size'                   => '',
			'type'                   => 'solid',
			'text'                   => '',
			'link'                   => '',
			'target'                 => '_self',
			'icon_position'          => '',
			'color'                  => '',
			'hover_color'            => '',
			'background_color'       => '',
			'hover_background_color' => '',
			'font_size'              => '',
			'font_weight'            => '',
			'text_transform'         => '',
			'margin'                 => '',
			'padding'                => '',
			'cutting_edge'           => '',
			'cutting_edge_value'     => '',
			'custom_class'           => '',
			'html_type'              => 'anchor',
			'input_name'             => '',
			'custom_attrs'           => array()
		);
		$default_atts = array_merge( $default_atts, overworld_edge_icon_collections()->getShortcodeParams() );
		$params       = shortcode_atts( $default_atts, $atts );

		if ( $params['html_type'] !== 'input' ) {
			$iconPackName   = overworld_edge_icon_collections()->getIconCollectionParamNameByKey( $params['icon_pack'] );
			$params['icon'] = $iconPackName ? $params[ $iconPackName ] : '';
		}

		$params['size'] = ! empty( $params['size'] ) ? $params['size'] : 'medium';
		$params['type'] = ! empty( $params['type'] ) ? $params['type'] : 'solid';

		$params['link']   = ! empty( $params['link'] ) ? $params['link'] : '#';
		$params['target'] = ! empty( $params['target'] ) ? $params['target'] : $default_atts['target'];

		$params['button_classes']      = $this->getButtonClasses( $params );
		$params['button_custom_attrs'] = ! empty( $params['custom_attrs'] ) ? $params['custom_attrs'] : array();
		$params['button_styles']       = $this->getButtonStyles( $params );
		$params['button_data']         = $this->getButtonDataAttr( $params );

		return overworld_core_get_shortcode_module_template_part( 'templates/' . $params['html_type'], 'button', '', $params );
	}

	private function getButtonStyles( $params ) {
		$styles = array();

		if ( ! empty( $params['color'] ) ) {
			$styles[] = 'color: ' . $params['color'];
		}

		if ( ! empty( $params['background_color'] ) && $params['type'] !== 'outline' ) {
			$styles[] = 'background-color: ' . $params['background_color'];
		}

		if ( ! empty( $params['font_size'] ) ) {
			$styles[] = 'font-size: ' . overworld_edge_filter_px( $params['font_size'] ) . 'px';
		}

		if ( ! empty( $params['font_weight'] ) && $params['font_weight'] !== '' ) {
			$styles[] = 'font-weight: ' . $params['font_weight'];
		}

		if ( ! empty( $params['text_transform'] ) ) {
			$styles[] = 'text-transform: ' . $params['text_transform'];
		}

		if ( $params['margin'] !== '' ) {
			$styles[] = 'margin: ' . $params['margin'];
		}

		if ( $params['padding'] !== '' ) {
			$styles[] = 'padding: ' . $params['padding'];
		}

		if ( $params['cutting_edge'] === 'yes' && $params['cutting_edge_value'] !== '' ) {
			$styles[] = 'clip-path: ' . $params['cutting_edge_value'];
		}

		return $styles;
	}

	private function getButtonDataAttr( $params ) {
		$data = array();

		if ( ! empty( $params['hover_color'] ) ) {
			$data['data-hover-color'] = $params['hover_color'];
		}

		if ( ! empty( $params['hover_background_color'] ) ) {
			$data['data-hover-bg-color'] = $params['hover_background_color'];
		}

		return $data;
	}

	private function getButtonClasses( $params ) {
		$buttonClasses = array(
			'edgtf-btn',
			'edgtf-btn-' . $params['size'],
			'edgtf-btn-' . $params['type']
		);

		if ( ! empty( $params['hover_background_color'] ) ) {
			$buttonClasses[] = 'edgtf-btn-custom-hover-bg';
		}

		if ( ! empty( $params['hover_color'] ) ) {
			$buttonClasses[] = 'edgtf-btn-custom-hover-color';
		}

		if ( ! empty( $params['icon'] ) ) {
			$buttonClasses[] = 'edgtf-btn-icon';
		}

		if ( ! empty( $params['icon_position'] ) ) {
			$buttonClasses[] = 'edgtf-btn-icon-' . $params['icon_position'];
		}

		if ( $params['cutting_edge'] === 'yes' ) {
			$buttonClasses[] = 'edgtf-btn-cutting-edge';
		}

		if ( ! empty( $params['custom_class'] ) ) {
			$buttonClasses[] = esc_attr( $params['custom_class'] );
		}

		return $buttonClasses;
	}
}