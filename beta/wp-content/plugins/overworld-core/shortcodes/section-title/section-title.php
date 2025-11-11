<?php
namespace OverworldCore\CPT\Shortcodes\SectionTitle;

use OverworldCore\Lib;

class SectionTitle implements Lib\ShortcodeInterface {
	private $base;
	
	function __construct() {
		$this->base = 'edgtf_section_title';
		
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'                      => esc_html__( 'Section Title', 'overworld-core' ),
					'base'                      => $this->base,
					'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
					'icon'                      => 'icon-wpb-section-title extended-custom-icon',
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
							'param_name'  => 'position',
							'heading'     => esc_html__( 'Horizontal Position', 'overworld-core' ),
							'value'       => array(
								esc_html__( 'Default', 'overworld-core' ) => '',
								esc_html__( 'Left', 'overworld-core' )    => 'left',
								esc_html__( 'Center', 'overworld-core' )  => 'center',
								esc_html__( 'Right', 'overworld-core' )   => 'right'
							),
							'save_always' => true
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'holder_padding',
							'heading'    => esc_html__( 'Holder Side Padding (px or %)', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'title',
							'heading'     => esc_html__( 'Title', 'overworld-core' ),
							'admin_label' => true
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'title_type',
							'heading'     => esc_html__( 'Title Type', 'overworld-core' ),
							'value'       => array(
								esc_html__( 'Default', 'overworld-core' )                => '',
								esc_html__( 'Simple Decorated', 'overworld-core' ) => 'simple-decorated',
								esc_html__( 'Text Decorated', 'overworld-core' )   => 'text-decorated'
							),
							'save_always' => true,
							'dependency'  => array( 'element' => 'title', 'not_empty' => true ),
							'group'       => esc_html__( 'Title Style', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'text_for_decoration',
							'heading'     => esc_html__( 'Text For Decoration', 'overworld-core' ),
							'dependency'  => array( 'element' => 'title_type', 'value' => 'text-decorated' ),
							'group'       => esc_html__( 'Title Style', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'title_tag',
							'heading'     => esc_html__( 'Title Tag', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_title_tag( true ) ),
							'save_always' => true,
							'dependency'  => array( 'element' => 'title', 'not_empty' => true ),
							'group'       => esc_html__( 'Title Style', 'overworld-core' )
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'title_color',
							'heading'    => esc_html__( 'Title Color', 'overworld-core' ),
							'dependency' => array( 'element' => 'title', 'not_empty' => true ),
							'group'      => esc_html__( 'Title Style', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'title_break_words',
							'heading'     => esc_html__( 'Position of Line Break', 'overworld-core' ),
							'description' => esc_html__( 'Enter the position of the word after which you would like to create a line break (e.g. if you would like the line break after the 3rd word, you would enter "3")', 'overworld-core' ),
							'dependency'  => array( 'element' => 'title', 'not_empty' => true ),
							'group'       => esc_html__( 'Title Style', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'disable_break_words',
							'heading'     => esc_html__( 'Disable Line Break for Smaller Screens', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
							'save_always' => true,
							'dependency'  => array( 'element' => 'title', 'not_empty' => true ),
							'group'       => esc_html__( 'Title Style', 'overworld-core' )
						),
						array(
							'type'       => 'textarea',
							'param_name' => 'text',
							'heading'    => esc_html__( 'Text', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'text_tag',
							'heading'     => esc_html__( 'Text Tag', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_title_tag( true, array( 'p' => 'p' ) ) ),
							'save_always' => true,
							'dependency'  => array( 'element' => 'text', 'not_empty' => true ),
							'group'       => esc_html__( 'Text Style', 'overworld-core' )
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'text_color',
							'heading'    => esc_html__( 'Text Color', 'overworld-core' ),
							'dependency' => array( 'element' => 'text', 'not_empty' => true ),
							'group'      => esc_html__( 'Text Style', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'text_font_size',
							'heading'    => esc_html__( 'Text Font Size (px)', 'overworld-core' ),
							'dependency' => array( 'element' => 'text', 'not_empty' => true ),
							'group'      => esc_html__( 'Text Style', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'text_line_height',
							'heading'    => esc_html__( 'Text Line Height (px)', 'overworld-core' ),
							'dependency' => array( 'element' => 'text', 'not_empty' => true ),
							'group'      => esc_html__( 'Text Style', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'text_font_weight',
							'heading'     => esc_html__( 'Text Font Weight', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_font_weight_array( true ) ),
							'save_always' => true,
							'dependency'  => array( 'element' => 'text', 'not_empty' => true ),
							'group'       => esc_html__( 'Text Style', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'text_margin',
							'heading'    => esc_html__( 'Text Top Margin (px)', 'overworld-core' ),
							'dependency' => array( 'element' => 'text', 'not_empty' => true ),
							'group'      => esc_html__( 'Text Style', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'button_text',
							'heading'     => esc_html__( 'Button Text', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'button_type',
							'heading'     => esc_html__( 'Button Type', 'overworld-core' ),
							'value'       => array(
								esc_html__( 'Solid', 'overworld-core' )     => 'solid',
								esc_html__( 'Outline', 'overworld-core' )   => 'outline',
								esc_html__( 'Simple', 'overworld-core' )    => 'simple'
							),
							'save_always' => true,
							'group'       => esc_html__( 'Button Style', 'overworld-core' )
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'button_size',
							'heading'    => esc_html__( 'Button Size', 'overworld-core' ),
							'value'      => array(
								esc_html__( 'Default', 'overworld-core' ) => '',
								esc_html__( 'Small', 'overworld-core' )   => 'small',
								esc_html__( 'Medium', 'overworld-core' )  => 'medium',
								esc_html__( 'Large', 'overworld-core' )   => 'large',
								esc_html__( 'Huge', 'overworld-core' )    => 'huge'
							),
							'save_always' => true,
							'group'       => esc_html__( 'Button Style', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'button_link',
							'heading'    => esc_html__( 'Button Link', 'overworld-core' ),
							'group'      => esc_html__( 'Button Style', 'overworld-core' )
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'button_target',
							'heading'    => esc_html__( 'Button Link Target', 'overworld-core' ),
							'value'      => array_flip( overworld_edge_get_link_target_array() ),
							'group'      => esc_html__( 'Button Style', 'overworld-core' )
						),
					), overworld_edge_icon_collections()->getVCParamsArray( array( 'element' => 'type', 'value' => array( 'solid', 'outline', 'simple' ) ), '', true ), array(
						array(
							'type'       => 'colorpicker',
							'param_name' => 'button_color',
							'heading'    => esc_html__( 'Button Color', 'overworld-core' ),
							'group'      => esc_html__( 'Button Style', 'overworld-core' )
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'button_hover_color',
							'heading'    => esc_html__( 'Button Hover Color', 'overworld-core' ),
							'group'      => esc_html__( 'Button Style', 'overworld-core' )
						),
						array(
							'type'       => 'textfield',
							'param_name' => 'button_top_margin',
							'heading'    => esc_html__( 'Button Top Margin (px)', 'overworld-core' ),
							'group'      => esc_html__( 'Button Style', 'overworld-core' )
						)
					) )
				)
			);
		}
	}
	
	public function render( $atts, $content = null ) {
		$args   = array(
			'custom_class'        => '',
			'position'            => '',
			'holder_padding'      => '',
			'title'               => '',
			'title_type'          => '',
			'text_for_decoration' => '',
			'title_tag'           => 'h3',
			'title_color'         => '',
			'title_break_words'   => '',
			'disable_break_words' => '',
			'text'                => '',
			'text_tag'            => 'p',
			'text_color'          => '',
			'text_font_size'      => '',
			'text_line_height'    => '',
			'text_font_weight'    => '',
			'text_margin'         => '',
			'button_text'         => '',
			'button_type'         => 'simple',
			'button_size'         => '',
			'button_link'         => '',
			'button_target'       => '_self',
			'button_color'        => '',
			'button_hover_color'  => '',
			'button_top_margin'   => ''
		);
		$args = array_merge( $args, overworld_edge_icon_collections()->getShortcodeParams() );
		$params = shortcode_atts( $args, $atts );

		$iconPackName             = overworld_edge_icon_collections()->getIconCollectionParamNameByKey( $params['icon_pack'] );
		$params['icon_pack_name'] = $iconPackName;

		$params['title_tag']         = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
		$params['holder_classes']    = $this->getHolderClasses( $params, $args );
		$params['holder_styles']     = $this->getHolderStyles( $params );
		$params['title']             = $this->getModifiedTitle( $params );
		$params['title_styles']      = $this->getTitleStyles( $params );
		$params['text_tag']          = ! empty( $params['text_tag'] ) ? $params['text_tag'] : $args['text_tag'];
		$params['text_styles']       = $this->getTextStyles( $params );
		$params['button_parameters'] = $this->getButtonParameters( $params );

		$html = overworld_core_get_shortcode_module_template_part( 'templates/section-title', 'section-title', '', $params );
		
		return $html;
	}
	
	private function getHolderClasses( $params, $args ) {
		$holderClasses = array();
		
		$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
		$holderClasses[] = ! empty( $params['position'] ) ? 'edgtf-' . $params['position'] . '-position' : '';
		$holderClasses[] = ! empty( $params['title_type'] ) ? 'edgtf-' . $params['title_type'] . '-title' : '';
		$holderClasses[] = ! empty( $params['title_tag'] ) ? 'edgtf-' . $params['title_tag'] . '-title' : '';
		$holderClasses[] = $params['disable_break_words'] === 'yes' ? 'edgtf-st-disable-title-break' : '';
		
		return implode( ' ', $holderClasses );
	}
	
	private function getHolderStyles( $params ) {
		$styles = array();
		
		if ( ! empty( $params['holder_padding'] ) ) {
			$styles[] = 'padding: 0 ' . $params['holder_padding'];
		}
		
		return implode( ';', $styles );
	}
	
	private function getModifiedTitle( $params ) {
		$title             = $params['title'];
		$title_break_words = str_replace( ' ', '', $params['title_break_words'] );
		
		if ( ! empty( $title ) ) {
			$split_title = explode( ' ', $title );
			
			if ( ! empty( $title_break_words ) ) {
				$title_break_words = intval( $title_break_words );
				if ( ! empty( $split_title[ $title_break_words - 1 ] ) ) {
					$split_title[ $title_break_words - 1 ] = $split_title[ $title_break_words - 1 ] . '<br />';
				}
			}
			
			$title = implode( ' ', $split_title );
		}
		
		return $title;
	}
	
	private function getTitleStyles( $params ) {
		$styles = array();
		
		if ( ! empty( $params['title_color'] ) ) {
			$styles[] = 'color: ' . $params['title_color'];
		}
		
		return implode( ';', $styles );
	}
	
	private function getTextStyles( $params ) {
		$styles = array();
		
		if ( ! empty( $params['text_color'] ) ) {
			$styles[] = 'color: ' . $params['text_color'];
		}
		
		if ( ! empty( $params['text_font_size'] ) ) {
			$styles[] = 'font-size: ' . overworld_edge_filter_px( $params['text_font_size'] ) . 'px';
		}
		
		if ( ! empty( $params['text_line_height'] ) ) {
			$styles[] = 'line-height: ' . overworld_edge_filter_px( $params['text_line_height'] ) . 'px';
		}
		
		if ( ! empty( $params['text_font_weight'] ) ) {
			$styles[] = 'font-weight: ' . $params['text_font_weight'];
		}
		
		if ( $params['text_margin'] !== '' ) {
			$styles[] = 'margin-top: ' . overworld_edge_filter_px( $params['text_margin'] ) . 'px';
		}
		
		return implode( ';', $styles );
	}
	
	private function getButtonParameters( $params ) {
		$button_params = array();
		
		if ( ! empty( $params['button_text'] ) ) {
			$button_params['text'] = $params['button_text'];
			$button_params['type'] = ! empty( $params['button_type'] ) ? $params['button_type'] : 'simple';
			$button_params['size'] = $params['button_size'];
			$button_params['link'] = ! empty( $params['button_link'] ) ? $params['button_link'] : '#';
			$button_params['target'] = ! empty( $params['button_target'] ) ? $params['button_target'] : '_self';

			if ( ! empty( $params['icon_pack'] ) ) {
				$button_params['icon_pack'] = $params['icon_pack'];
			}

			if ( ! empty( $params['icon_pack_name'] ) ) {
				$button_params[$params['icon_pack_name']] = $params[$params['icon_pack_name']];
			}

			if ( ! empty( $params['button_color'] ) ) {
				$button_params['color'] = $params['button_color'];
			}
			
			if ( ! empty( $params['button_hover_color'] ) ) {
				$button_params['hover_color'] = $params['button_hover_color'];
			}
			
			if ( $params['button_top_margin'] !== '' ) {
				$button_params['margin'] = intval( $params['button_top_margin'] ) . 'px 0 0';
			}
		}
		
		return $button_params;
	}
}