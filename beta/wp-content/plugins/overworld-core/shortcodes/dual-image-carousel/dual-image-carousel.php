<?php
namespace OverworldCore\CPT\Shortcodes\DualImageCarousel;

use OverworldCore\Lib;

class DualImageCarousel implements Lib\ShortcodeInterface {
	private $base;

	public function __construct() {
		$this->base = 'edgtf_dual_image_carousel';

		add_action('vc_before_init', array($this, 'vcMap'));
	}

	/**
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/*
	 * Maps shortcode to Visual Composer. Hooked on vc_before_init
	 */
	public function vcMap() {
		if (function_exists('vc_map')) {
			vc_map(
				array(
					'name'                      => esc_html__('Dual Image Carousel', 'overworld-core'),
					'base'                      => $this->base,
					'category'                  => esc_html__('by OVERWORLD', 'overworld-core'),
					'icon'                      => 'icon-wpb-dual-image-carousel extended-custom-icon',
					'allowed_container_element' => 'vc_row',
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'custom_class',
							'heading'     => esc_html__( 'Custom CSS Class', 'overworld-core' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'overworld-core' )
						),
                        array(
                            'type'        => 'attach_image',
                            'param_name'  => 'section_background_image',
                            'heading'     => esc_html__('Section Background Image', 'overworld-core'),
                            'description' => esc_html__('Select image from media library', 'overworld-core')
                        ),
						array(
							'type'        => 'textfield',
							'param_name'  => 'foreground_slides_position',
						 	'heading'     => esc_html__('Foreground slides position', 'overworld-core'),
						 	'description' => esc_html__( 'Default value is -25%', 'overworld-core' ),
						),
						array(
							'type'       => 'param_group',
							'heading'    => esc_html__('Dual Image Carousel Slides', 'overworld-core'),
							'param_name' => 'dual_image_carousel_slides',
							'value'      => '',
							'params'     => array(
								array(
									'type'        => 'attach_image',
									'param_name'  => 'background_image',
									'heading'     => esc_html__('Background Image', 'overworld-core'),
									'description' => esc_html__('Select image from media library', 'overworld-core')
								),
								array(
									'type'        => 'attach_image',
									'param_name'  => 'foreground_image',
									'heading'     => esc_html__('Foreground Image', 'overworld-core'),
									'description' => esc_html__('Select image from media library', 'overworld-core')
								)
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
	 * @return string
	 */
	public function render($atts, $content = null) {
		$args = array(
			'custom_class'               => '',
			'section_background_image'   => '',
			'foreground_slides_position' => '',
			'dual_image_carousel_slides' => ''
		);

		$params = shortcode_atts($args, $atts);

		$params['content'] = $content;

		$params['dual_image_carousel'] = vc_param_group_parse_atts($atts['dual_image_carousel_slides']);
		$params['data_params'] = $this->getDataParams($params);
		$params['dualImagesCarouselClasses'] = $this->getDualImagesCarouselClasses($params);
        $params['dualImagesCarouselStyle'] = $this->getDualImagesCarouselStyle($params);

		//Get HTML from template
		return overworld_core_get_shortcode_module_template_part( 'templates/dual-image-carousel', 'dual-image-carousel', '', $params );
	}

	/**
	 * Return Fullscreen Objects data params
	 *
	 * @param $params
	 * @return array
	 */
	private function getDataParams($params) {
		$data = array();

		if (!empty($params['foreground_slides_position'])) {	
			$data['data-foreground-slides-position'] = $params['foreground_slides_position'];
		}

		if (!empty($params['dual_image_carousel'])) {
			$data['data-number-of-items'] = count($params['dual_image_carousel']);
		}

		return $data;
	}

	private function getDualImagesCarouselClasses( $params ) {
		$dualImagesCarouselClasses = array(
			'edgtf-dual-image-carousel',
			'swiper-container', 
			'full-page'
		);

		if ( ! empty( $params['custom_class'] ) ) {
			$dualImagesCarouselClasses[] = esc_attr( $params['custom_class'] );
		}

		return $dualImagesCarouselClasses;
	}

    private function getDualImagesCarouselStyle( $params ) {
        $style = array();

        if(!empty($params['section_background_image'])) {
            $style[] = 'background-image: url(' . wp_get_attachment_url($params['section_background_image']) . ')';
        }

        return $style;
    }
}