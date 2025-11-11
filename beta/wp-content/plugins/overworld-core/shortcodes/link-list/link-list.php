<?php
namespace OverworldCore\CPT\Shortcodes\LinkList;

use OverworldCore\Lib;

class LinkList implements Lib\ShortcodeInterface {
	private $base;

	public function __construct() {
		$this->base = 'edgtf_link_list';

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
					'name'                      => esc_html__('Link List', 'overworld-core'),
					'base'                      => $this->base,
					'category'                  => esc_html__('by OVERWORLD', 'overworld-core'),
					'icon'                      => 'icon-wpb-link-list extended-custom-icon',
					'allowed_container_element' => 'vc_row',
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'custom_class',
							'heading'     => esc_html__( 'Custom CSS Class', 'overworld-core' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'overworld-core' )
						),
						array(
							'type'        => 'textfield',
							'param_name'  => 'margin',
							'heading'     => esc_html__( 'Links Margin', 'overworld-core' ),
							'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'overworld-core' )
						),
						array(
							'type'       => 'param_group',
							'heading'    => esc_html__('Links', 'overworld-core'),
							'param_name' => 'links',
							'value'      => '',
							'params'     => array(
								array(
									'type'        => 'attach_image',
									'param_name'  => 'image',
									'heading'     => esc_html__( 'Image', 'overworld-core' ),
									'description' => esc_html__( 'Select image from media library', 'overworld-core' )
								),
								array(
									'type'        => 'textfield',
									'param_name'  => 'text',
									'heading'     => esc_html__( 'Text', 'overworld-core' )
								),
								array(
									'type'       => 'textfield',
									'param_name' => 'link',
									'heading'    => esc_html__( 'Link', 'overworld-core' )
								),
								array(
									'type'       => 'dropdown',
									'param_name' => 'target',
									'heading'    => esc_html__( 'Target', 'overworld-core' ),
									'value'      => array_flip( overworld_edge_get_link_target_array() )
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
			'custom_class' => '',
			'margin'       => '',
			'links'        => ''
		);

		$params = shortcode_atts($args, $atts);

		$params['content'] = $content;

		$params['links']             = vc_param_group_parse_atts( $atts['links'] );
		$params['link_list_classes'] = $this->getLinkListClasses( $params );
		$params['links_styles']      = $this->getLinkListStyles( $params );
		$params['this_object']       = $this;

		//Get HTML from template
		return overworld_core_get_shortcode_module_template_part( 'templates/link-list', 'link-list', '', $params );
	}

	private function getLinkListClasses( $params ) {
		$classes = array(
			'edgtf-link-list-holder'
		);

		if ( ! empty( $params['custom_class'] ) ) {
			$classes[] = esc_attr( $params['custom_class'] );
		}

		return $classes;
	}

    public function getLinkListStyles( $params ) {
        $style = array();

		if ( $params['margin'] !== '' ) {
			$styles[] = 'margin: ' . $params['margin'];
		}

        return $style;
    }
}