<?php

namespace OverworldCore\CPT\Shortcodes\BlogSlider;

use OverworldCore\Lib;

class BlogSlider implements Lib\ShortcodeInterface {
	private $base;
	
	function __construct() {
		$this->base = 'edgtf_blog_slider';
		
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
		
		//Category filter
		add_filter( 'vc_autocomplete_edgtf_blog_slider_category_callback', array(
			&$this,
			'blogListCategoryAutocompleteSuggester',
		), 10, 1 ); // Get suggestion(find). Must return an array
		
		//Category render
		add_filter( 'vc_autocomplete_edgtf_blog_slider_category_render', array(
			&$this,
			'blogListCategoryAutocompleteRender',
		), 10, 1 ); // Get suggestion(find). Must return an array
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map(
			array(
				'name'                      => esc_html__( 'Blog Slider', 'overworld' ),
				'base'                      => $this->base,
				'icon'                      => 'icon-wpb-blog-slider extended-custom-icon',
				'category'                  => esc_html__( 'by OVERWORLD', 'overworld' ),
				'allowed_container_element' => 'vc_row',
				'params'                    => array(
					array(
						'type'        => 'dropdown',
						'param_name'  => 'slider_type',
						'heading'     => esc_html__( 'Type', 'overworld' ),
						'value'       => array(
							esc_html__( 'Slider', 'overworld' )            => 'slider',
							esc_html__( 'Carousel', 'overworld' )          => 'carousel',
							esc_html__( 'Carousel Centered', 'overworld' ) => 'carousel-centered'
						),
						'save_always' => true
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'number_of_posts',
						'heading'    => esc_html__( 'Number of Posts', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'orderby',
						'heading'     => esc_html__( 'Order By', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_query_order_by_array() ),
						'save_always' => true
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'order',
						'heading'     => esc_html__( 'Order', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_query_order_array() ),
						'save_always' => true
					),
					array(
						'type'        => 'autocomplete',
						'param_name'  => 'category',
						'heading'     => esc_html__( 'Category', 'overworld' ),
						'description' => esc_html__( 'Enter one category slug (leave empty for showing all categories)', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'image_size',
						'heading'     => esc_html__( 'Image Size', 'overworld' ),
						'value'       => array(
							esc_html__( 'Original', 'overworld' )  => 'full',
							esc_html__( 'Square', 'overworld' )    => 'overworld_edge_image_square',
							esc_html__( 'Landscape', 'overworld' ) => 'overworld_edge_image_landscape',
							esc_html__( 'Portrait', 'overworld' )  => 'overworld_edge_image_portrait',
							esc_html__( 'Thumbnail', 'overworld' ) => 'thumbnail',
							esc_html__( 'Medium', 'overworld' )    => 'medium',
							esc_html__( 'Large', 'overworld' )     => 'large',
							esc_html__( 'Custom', 'overworld' )    => 'custom'
						),
						'save_always' => true
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'custom_image_width',
						'heading'     => esc_html__( 'Custom Image Width', 'overworld' ),
						'description' => esc_html__( 'Enter image width in px', 'overworld' ),
						'dependency'  => array( 'element' => 'image_size', 'value' => 'custom' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'custom_image_height',
						'heading'     => esc_html__( 'Custom Image Height', 'overworld' ),
						'description' => esc_html__( 'Enter image height in px', 'overworld' ),
						'dependency'  => array( 'element' => 'image_size', 'value' => 'custom' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'enable_shadow',
						'heading'     => esc_html__( 'Enable Shadow', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						'save_always' => true,
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'title_tag',
						'heading'    => esc_html__( 'Title Tag', 'overworld' ),
						'value'      => array_flip( overworld_edge_get_title_tag( true ) ),
						'group'      => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'title_transform',
						'heading'    => esc_html__( 'Title Text Transform', 'overworld' ),
						'value'      => array_flip( overworld_edge_get_text_transform_array( true ) ),
						'group'      => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'post_info_author',
						'heading'     => esc_html__( 'Enable Post Info Author', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, false ) ),
						'save_always' => true,
						'group'       => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'post_info_date',
						'heading'     => esc_html__( 'Enable Post Info Date', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						'save_always' => true,
						'group'       => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'post_info_category',
						'heading'     => esc_html__( 'Enable Post Info Category', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						'save_always' => true,
						'group'       => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'post_info_comments',
						'heading'     => esc_html__( 'Enable Post Info Comments', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false ) ),
						'save_always' => true,
						'group'       => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'slider_navigation',
						'heading'     => esc_html__( 'Enable Slider Navigation Arrows', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						'save_always' => true,
						'group'       => esc_html__( 'Post Info', 'overworld' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'slider_pagination',
						'heading'     => esc_html__( 'Enable Slider Pagination', 'overworld' ),
						'value'       => array_flip( overworld_edge_get_yes_no_select_array( false, true ) ),
						'save_always' => true,
						'group'       => esc_html__( 'Post Info', 'overworld' )
					),
				)
			)
		);
	}
	
	public function render( $atts, $content = null ) {
		$default_atts = array(
			'slider_type'         => 'slider',
			'number_of_posts'     => '-1',
			'orderby'             => 'title',
			'order'               => 'ASC',
			'category'            => '',
			'image_size'          => 'full',
			'custom_image_width'  => '',
			'custom_image_height' => '',
			'enable_shadow'       => 'yes',
			'title_tag'           => 'h4',
			'title_transform'     => '',
			'post_info_author'    => 'no',
			'post_info_date'      => 'yes',
			'post_info_category'  => 'yes',
			'post_info_comments'  => 'no',
			'slider_navigation'   => 'yes',
			'slider_pagination'   => 'yes'
		);
		$params       = shortcode_atts( $default_atts, $atts );
		
		$queryArray             = $this->generateBlogQueryArray( $params );
		$query_result           = new \WP_Query( $queryArray );
		$params['query_result'] = $query_result;
		
		$params['slider_type']    = ! empty( $params['slider_type'] ) ? $params['slider_type'] : $default_atts['slider_type'];
		$params['slider_classes'] = $this->getSliderClasses( $params );
		$params['slider_data']    = $this->getSliderData( $params );
		
		ob_start();
		
		overworld_edge_get_module_template_part( 'shortcodes/blog-slider/holder', 'blog', '', $params );
		
		$html = ob_get_contents();
		
		ob_end_clean();
		
		return $html;
	}
	
	public function generateBlogQueryArray( $params ) {
		$queryArray = array(
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'orderby'        => $params['orderby'],
			'order'          => $params['order'],
			'posts_per_page' => $params['number_of_posts'],
			'post__not_in'   => get_option( 'sticky_posts' ),
			'enable_shadow'  => 'yes',
		);
		
		if ( ! empty( $params['category'] ) ) {
			$queryArray['category_name'] = $params['category'];
		}
		
		return $queryArray;
	}
	
	public function getSliderClasses( $params ) {
		$holderClasses = array();
		
		$holderClasses[] = 'edgtf-bs-' . $params['slider_type'];
		
		if ( $params['enable_shadow'] === 'yes' ) {
			$holderClasses[] = 'edgtf-bs-shadow';
		}
		
		return implode( ' ', $holderClasses );
	}
	
	private function getSliderData( $params ) {
		$type        = $params['slider_type'];
		$slider_data = array();

		if ( $type == 'carousel' ) {
			$slider_data['data-number-of-items']             = '2';
			$slider_data['data-slider-margin']               = '80';
			$slider_data['data-enable-navigation']           = 'no';
		} else if ( $type == 'carousel-centered' ) {
			$slider_data['data-number-of-items']             = '2';
			$slider_data['data-slider-margin']               = '65';
			$slider_data['data-enable-center']               = 'yes';
			$slider_data['data-slider-custom-padding']       = 'yes';
			$slider_data['data-slider-custom-padding-value'] = '0.07';
			$slider_data['data-enable-navigation']           = 'yes';
			$slider_data['data-enable-pagination']           = 'yes';
		} else {
			$slider_data['data-number-of-items']             = '1';
			$slider_data['data-enable-pagination']           = 'yes';
		}
		
		$slider_data['data-enable-navigation'] = ! empty( $params['slider_navigation'] ) ? $params['slider_navigation'] : '';
		$slider_data['data-enable-pagination'] = ! empty( $params['slider_pagination'] ) ? $params['slider_pagination'] : '';
		
		return $slider_data;
	}
	
	/**
	 * Filter categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function blogListCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );
		
		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['category_title'] ) > 0 ) ? esc_html__( 'Category', 'overworld' ) . ': ' . $value['category_title'] : '' );
				$results[]     = $data;
			}
		}
		
		return $results;
	}
	
	/**
	 * Find categories by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function blogListCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get category
			$category = get_term_by( 'slug', $query, 'category' );
			if ( is_object( $category ) ) {
				
				$category_slug  = $category->slug;
				$category_title = $category->name;
				
				$category_title_display = '';
				if ( ! empty( $category_title ) ) {
					$category_title_display = esc_html__( 'Category', 'overworld' ) . ': ' . $category_title;
				}
				
				$data          = array();
				$data['value'] = $category_slug;
				$data['label'] = $category_title_display;
				
				return ! empty( $data ) ? $data : false;
			}
			
			return false;
		}
		
		return false;
	}
}
