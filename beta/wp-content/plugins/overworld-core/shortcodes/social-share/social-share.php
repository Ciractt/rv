<?php

namespace OverworldCore\CPT\Shortcodes\SocialShare;

use OverworldCore\Lib;

class SocialShare implements Lib\ShortcodeInterface {
	private $base;
	private $socialNetworks;

	function __construct() {
		$this->base           = 'edgtf_social_share';
		$this->socialNetworks = array(
			'facebook',
			'twitter',
			'linkedin',
			'tumblr',
			'pinterest',
			'vk'
		);
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
	}

	public function getBase() {
		return $this->base;
	}

	public function getSocialNetworks() {
		return $this->socialNetworks;
	}

	public function vcMap() {
		if ( function_exists( 'vc_map' ) ) {
			vc_map( array(
				'name'                      => esc_html__( 'Social Share', 'overworld-core' ),
				'base'                      => $this->getBase(),
				'icon'                      => 'icon-wpb-social-share extended-custom-icon',
				'category'                  => esc_html__( 'by OVERWORLD', 'overworld-core' ),
				'allowed_container_element' => 'vc_row',
				'params'                    => array(
					array(
						'type'       => 'dropdown',
						'param_name' => 'type',
						'heading'    => esc_html__( 'Type', 'overworld-core' ),
						'value'      => array(
							esc_html__( 'List', 'overworld-core' )     => 'list',
							esc_html__( 'Dropdown', 'overworld-core' ) => 'dropdown',
							esc_html__( 'Text', 'overworld-core' )     => 'text'
						)
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'dropdown_behavior',
						'heading'     => esc_html__( 'DropDown Hover Behavior', 'overworld-core' ),
						'value'       => array(
							esc_html__( 'On Bottom Animation', 'overworld-core' ) => 'bottom',
							esc_html__( 'On Right Animation', 'overworld-core' )  => 'right',
							esc_html__( 'On Left Animation', 'overworld-core' )   => 'left'
						),
						'save_always' => true,
						'dependency'  => array( 'element' => 'type', 'value' => array( 'dropdown' ) )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'icon_type',
						'heading'     => esc_html__( 'Icons Type', 'overworld-core' ),
						'value'       => array(
							esc_html__( 'Font Awesome', 'overworld-core' ) => 'font-awesome',
							esc_html__( 'Font Elegant', 'overworld-core' ) => 'font-elegant',
							esc_html__( 'Ion Icons', 'overworld-core' )    => 'ion-icons'
						),
						'save_always' => true,
						'dependency'  => array( 'element' => 'type', 'value' => array( 'list', 'dropdown' ) )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'title',
						'heading'    => esc_html__( 'Social Share Title', 'overworld-core' )
					)
				)
			) );
		}
	}

	public function render( $atts, $content = null ) {
		$args   = array(
			'type'              => 'list',
			'dropdown_behavior' => 'right',
			'icon_type'         => 'font-awesome',
			'title'             => ''
		);
		$params = shortcode_atts( $args, $atts );

		//Is social share enabled
		$params['enable_social_share'] = overworld_edge_options()->getOptionValue( 'enable_social_share' ) === 'yes';

		//Is social share enabled for post type
		$post_type         = str_replace( '-', '_', get_post_type() );
		$params['enabled'] = overworld_edge_options()->getOptionValue( 'enable_social_share_on_' . $post_type ) === 'yes';

		//Social Networks Data
		$params['networks'] = $this->getSocialNetworksParams( $params );

		$params['dropdown_class'] = ! empty( $params['dropdown_behavior'] ) ? 'edgtf-' . $params['dropdown_behavior'] : 'edgtf-' . $args['dropdown_behavior'];

		$html = '';

		if ( $params['enable_social_share'] && $params['enabled'] ) {
			$html = overworld_core_get_shortcode_module_template_part( 'templates/' . $params['type'], 'social-share', '', $params );
		}

		return $html;
	}

	/**
	 * Get Social Networks data to display
	 * @return array
	 */
	private function getSocialNetworksParams( $params ) {
		$networks   = array();
		$icons_type = $params['icon_type'];
		$type       = $params['type'];

		foreach ( $this->socialNetworks as $net ) {
			$html = '';

			if ( overworld_edge_options()->getOptionValue( 'enable_' . $net . '_share' ) == 'yes' ) {
				$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$params = array(
					'name' => $net,
					'type' => $type
				);

				$params['link'] = $this->getSocialNetworkShareLink( $net, $image );

				if ( $type == 'text' ) {
					$params['text'] = $this->getSocialNetworkText( $net );
				} else {
					$params['icon'] = $this->getSocialNetworkIcon( $net, $icons_type );
				}

				$params['custom_icon'] = ( overworld_edge_options()->getOptionValue( $net . '_icon' ) ) ? overworld_edge_options()->getOptionValue( $net . '_icon' ) : '';

				$html = overworld_core_get_shortcode_module_template_part( 'templates/parts/network', 'social-share', '', $params );
			}

			$networks[ $net ] = $html;
		}

		return $networks;
	}

	/**
	 * Get share link for networks
	 *
	 * @param $net
	 * @param $image
	 *
	 * @return string
	 */
	private function getSocialNetworkShareLink( $net, $image ) {
		$image = ! empty( $image ) && isset( $image[0] ) ? $image : array( '' );

		switch ( $net ) {
			case 'facebook':
				if ( wp_is_mobile() ) {
					$link = 'window.open(\'http://m.facebook.com/sharer.php?u=' . get_permalink() . '\');';
				} else {
					$link = 'window.open(\'http://www.facebook.com/sharer/sharer.php?u=' . get_permalink() . '\');';
				}
				break;
			case 'twitter':
				$count_char  = ( isset( $_SERVER['https'] ) ) ? 23 : 22;
				$twitter_via = ( overworld_edge_options()->getOptionValue( 'twitter_via' ) !== '' ) ? esc_attr__( ' via ', 'overworld-core' ) . overworld_edge_options()->getOptionValue( 'twitter_via' ) . ' ' : '';
				$link        = 'window.open(\'https://twitter.com/intent/tweet?text=' . urlencode( overworld_edge_the_excerpt_max_charlength( $count_char ) . $twitter_via ) . ' ' . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');';
				break;
			case 'linkedin':
				$link = 'popUp=window.open(\'https://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode( get_permalink() ) . '&amp;title=' . urlencode( get_the_title() ) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;';
				break;
			case 'tumblr':
				$link = 'popUp=window.open(\'https://www.tumblr.com/share/link?url=' . urlencode( get_permalink() ) . '&amp;name=' . urlencode( get_the_title() ) . '&amp;description=' . urlencode( get_the_excerpt() ) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;';
				break;
			case 'pinterest':
				$link = 'popUp=window.open(\'https://pinterest.com/pin/create/button/?url=' . urlencode( get_permalink() ) . '&amp;description=' . sanitize_title( get_the_title() ) . '&amp;media=' . urlencode( $image[0] ) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;';
				break;
			case 'vk':
				$link = 'popUp=window.open(\'https://vkontakte.ru/share.php?url=' . urlencode( get_permalink() ) . '&amp;title=' . urlencode( get_the_title() ) . '&amp;description=' . urlencode( get_the_excerpt() ) . '&amp;image=' . urlencode( $image[0] ) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;';
				break;
			default:
				$link = '';
		}

		return $link;
	}

	private function getSocialNetworkIcon( $net, $type ) {
		switch ( $net ) {
			case 'facebook':
				if ( $type == 'ion-icons' ) {
					$icon = 'ion-social-facebook';
				} else {
					$icon = ( $type == 'font-elegant' ) ? 'social_facebook' : 'fab fa-facebook-f';
				}
				break;
			case 'twitter':
				if ( $type == 'ion-icons' ) {
					$icon = 'ion-social-twitter';
				} else {
					$icon = ( $type == 'font-elegant' ) ? 'social_twitter' : 'fab fa-twitter';
				}
				break;
			case 'linkedin':
				if ( $type == 'ion-icons' ) {
					$icon = 'ion-social-linkedin';
				} else {
					$icon = ( $type == 'font-elegant' ) ? 'social_linkedin' : 'fab fa-linkedin-in';
				}
				break;
			case 'tumblr':
				if ( $type == 'ion-icons' ) {
					$icon = 'ion-social-tumblr';
				} else {
					$icon = ( $type == 'font-elegant' ) ? 'social_tumblr' : 'fab fa-tumblr';
				}
				break;
			case 'pinterest':
				if ( $type == 'ion-icons' ) {
					$icon = 'ion-social-pinterest';
				} else {
					$icon = ( $type == 'font-elegant' ) ? 'social_pinterest' : 'fab fa-pinterest';
				}
				break;
			case 'vk':
				$icon = 'fab fa-vk';
				break;
			default:
				$icon = '';
		}

		return $icon;
	}

	private function getSocialNetworkText( $net ) {
		switch ( $net ) {
			case 'facebook':
				$text = esc_html__( 'fb', 'overworld-core' );
				break;
			case 'twitter':
				$text = esc_html__( 'tw', 'overworld-core' );
				break;
			case 'linkedin':
				$text = esc_html__( 'lnkd', 'overworld-core' );
				break;
			case 'tumblr':
				$text = esc_html__( 'tmb', 'overworld-core' );
				break;
			case 'pinterest':
				$text = esc_html__( 'pin', 'overworld-core' );
				break;
			case 'vk':
				$text = esc_html__( 'vk', 'overworld-core' );
				break;
			default:
				$text = '';
		}

		return $text;
	}
}