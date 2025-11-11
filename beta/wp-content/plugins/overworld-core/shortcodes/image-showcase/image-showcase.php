<?php
namespace OverworldCore\CPT\Shortcodes\ImageShowcase;

use OverworldCore\Lib;

class ImageShowcase implements Lib\ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'edgtf_image_showcase';
		
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'                    => esc_html__( 'Image Showcase', 'overworld-core' ),
					'base'                    => $this->getBase(),
					'category'                => esc_html__( 'by OVERWORLD', 'overworld-core' ),
					'icon'                    => 'icon-wpb-image-showcase extended-custom-icon',
					'as_parent'               => array( 'except' => 'edgtf_elements_holder, edgtf_elements_holder_item, vc_accordion' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'custom_class',
							'heading'     => esc_html__( 'Custom CSS Class', 'overworld-core' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'overworld-core' )
						),
						array(
							'type'        => 'attach_images',
							'param_name'  => 'images',
							'heading'     => esc_html__( 'Images', 'overworld-core' ),
							'description' => esc_html__( 'Select images from media library', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'image_size',
							'heading'     => esc_html__( 'Image Size', 'overworld-core' ),
							'description' => esc_html__( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "full" size', 'overworld-core' )
						),
						array(
							'type'       => 'colorpicker',
							'param_name' => 'background_color',
							'heading'    => esc_html__( 'Content Background Color', 'overworld-core' )
						),
						array(
							'type'        => 'attach_image',
							'param_name'  => 'background_image',
							'heading'     => esc_html__( 'Content Background Image', 'overworld-core' ),
							'description' => esc_html__( 'Select image from media library', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'slider_loop',
							'heading'     => esc_html__( 'Enable Slider Loop', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
							'save_always' => true,
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'slider_autoplay',
							'heading'     => esc_html__( 'Enable Slider Autoplay', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
							'save_always' => true,
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'slider_speed',
							'heading'     => esc_html__( 'Slide Duration', 'overworld-core' ),
							'description' => esc_html__( 'Default value is 5000 (ms)', 'overworld-core' ),
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'slider_speed_animation',
							'heading'     => esc_html__( 'Slide Animation Duration', 'overworld-core' ),
							'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 600.', 'overworld-core' ),
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'enable_full_height',
							'heading'     => esc_html__( 'Enable Full Height Slider', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
							'save_always' => true,
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'slider_navigation',
							'heading'     => esc_html__( 'Enable Slider Navigation Arrows', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
							'save_always' => true,
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'slider_pagination',
							'heading'     => esc_html__( 'Enable Slider Pagination', 'overworld-core' ),
							'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
							'save_always' => true,
							'group'       => esc_html__( 'Slider Settings', 'overworld-core' )
						)
					)
				)
			);
		}
	}
	
	public function render( $atts, $content = null ) {
		$args   = array(
			'custom_class'              => '',
			'images'                    => '',
			'image_size'                => 'full',
			'background_color'          => '',
			'background_image'          => '',
			'slider_loop'               => 'yes',
			'slider_autoplay'           => 'yes',
			'slider_speed'              => '5000',
			'slider_speed_animation'    => '600',
			'enable_full_height'        => 'no',
			'slider_navigation'         => 'yes',
			'slider_pagination'         => 'yes'
		);
		$params = shortcode_atts( $args, $atts );

		$params['enable_full_height'] = ! empty( $params['enable_full_height'] ) ? $params['enable_full_height'] : $args['enable_full_height'];

		$params['content']           = $content;
		$params['holder_classes']    = $this->getHolderClasses( $params );
		$params['slider_data']       = $this->getSliderData( $params );
		$params['content_styles']    = $this->getContentStyles( $params );
		$params['images']            = $this->getShowcaseImages( $params );
		$params['image_size']        = $this->getImageSize( $params['image_size'] );

		$html = overworld_core_get_shortcode_module_template_part( 'templates/image-showcase', 'image-showcase', '', $params );
		
		return $html;
	}
	
	private function getHolderClasses( $params ) {
		$holderClasses = array('edgtf-owl-nav-big');

		$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
		$holderClasses[] = $params['enable_full_height'] === 'yes' ? 'edgtf-is-full-height' : 'edgtf-disable-bottom-space';

		return implode( ' ', $holderClasses );
	}

	private function getSliderData( $params ) {
		$slider_data = array();
		
		$slider_data['data-number-of-items']        = '1';
		$slider_data['data-enable-loop']            = ! empty( $params['slider_loop'] ) ? $params['slider_loop'] : '';
		$slider_data['data-enable-autoplay']        = ! empty( $params['slider_autoplay'] ) ? $params['slider_autoplay'] : '';
		$slider_data['data-slider-speed']           = ! empty( $params['slider_speed'] ) ? $params['slider_speed'] : '5000';
		$slider_data['data-slider-speed-animation'] = ! empty( $params['slider_speed_animation'] ) ? $params['slider_speed_animation'] : '600';
		$slider_data['data-enable-navigation']      = ! empty( $params['slider_navigation'] ) ? $params['slider_navigation'] : '';
		$slider_data['data-enable-pagination']      = ! empty( $params['slider_pagination'] ) ? $params['slider_pagination'] : '';

		$slider_data['data-nav-size']               = 'big';
		
		return $slider_data;
	}

	private function getContentStyles( $params ) {
		$styles = array();

		if ( ! empty( $params['background_color'] ) ) {
			$styles[] = 'background-color: ' . $params['background_color'];
		}

		if ( ! empty( $params['background_image'] ) ) {
			$background_image = $this->getBackgroundImage($params);

			if ( ! empty( $background_image['url'] ) ) {
				$styles[] = 'background-image: url(' . esc_url( $background_image['url'] ) . ')';
				$styles[] = 'background-repeat: no-repeat';
				$styles[] = 'background-position: right top';
				$styles[] = 'background-attachment: fixed';
			}
		}

		return implode( ';', $styles );
	}

	private function getShowcaseImages( $params ) {
		$image_ids = array();
		$images    = array();
		$i         = 0;
		
		if ( $params['images'] !== '' ) {
			$image_ids = explode( ',', $params['images'] );
		}
		
		foreach ( $image_ids as $id ) {
			
			$image['image_id'] = $id;
			$image_original    = wp_get_attachment_image_src( $id, 'full' );
			$image['url']      = $image_original[0];
			$image['title']    = get_the_title( $id );
			$image['alt']      = get_post_meta( $id, '_wp_attachment_image_alt', true );
			
			$images[ $i ] = $image;
			$i ++;
		}
		
		return $images;
	}

	private function getBackgroundImage( $params ) {
		$image_id = '';
		$image    = array();

		if ( $params['background_image'] !== '' ) {
			$image_id = $params['background_image'];
		}

		$image['image_id'] = $image_id;
		$image_original    = wp_get_attachment_image_src( $image_id, 'full' );
		$image['url']      = $image_original[0];

		return $image;
	}

	private function getImageSize( $image_size ) {
		$image_size = trim( $image_size );
		//Find digits
		preg_match_all( '/\d+/', $image_size, $matches );
		if ( in_array( $image_size, array( 'thumbnail', 'thumb', 'medium', 'large', 'full' ) ) ) {
			return $image_size;
		} elseif ( ! empty( $matches[0] ) ) {
			return array(
				$matches[0][0],
				$matches[0][1]
			);
		} else {
			return 'thumbnail';
		}
	}
}