<?php

if ( class_exists( 'OverworldCoreClassWidget' ) ) {
	class OverworldEdgeClassRecentPosts extends OverworldCoreClassWidget {
		public function __construct() {
			parent::__construct(
				'edgtf_recent_posts',
				esc_html__( 'Overworld Recent Posts', 'overworld' ),
				array( 'description' => esc_html__( 'Select recent posts that you would like to display', 'overworld' ) )
			);
			
			$this->setParams();
		}
		
		protected function setParams() {
			$post_types = apply_filters( 'overworld_edge_filter_search_post_type_widget_params_post_type', array( 'post' => esc_html__( 'Post', 'overworld' ) ) );
			
			$this->params = array(
				array(
					'type'  => 'textfield',
					'name'  => 'widget_bottom_margin',
					'title' => esc_html__( 'Widget Bottom Margin (px)', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'widget_title',
					'title' => esc_html__( 'Widget Title', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'widget_title_bottom_margin',
					'title' => esc_html__( 'Widget Title Bottom Margin (px)', 'overworld' )
				),
				array(
					'type'        => 'dropdown',
					'name'        => 'post_type',
					'title'       => esc_html__( 'Post Type', 'overworld' ),
					'description' => esc_html__( 'Choose post type that you want to be searched for', 'overworld' ),
					'options'     => $post_types
				),
				array(
					'type'    => 'dropdown',
					'name'    => 'title_tag',
					'title'   => esc_html__( 'Title Tag', 'overworld' ),
					'options' => overworld_edge_get_title_tag( true, array( 'p' => 'p' ) )
				)
			);
		}
		
		public function widget( $args, $instance ) {
			
			if ( ! is_array( $instance ) ) {
				$instance = array();
			}
			
			$widget_styles = array();
			if ( isset( $instance['widget_bottom_margin'] ) && $instance['widget_bottom_margin'] !== '' ) {
				$widget_styles[] = 'margin-bottom: ' . overworld_edge_filter_px( $instance['widget_bottom_margin'] ) . 'px';
			}
			
			$widget_title_styles = array();
			if ( isset( $instance['widget_title_bottom_margin'] ) && $instance['widget_title_bottom_margin'] !== '' ) {
				$widget_title_styles[] = 'margin-bottom: ' . overworld_edge_filter_px( $instance['widget_title_bottom_margin'] ) . 'px';
			}
			
			$type = $instance['post_type'] !== '' ? $instance['post_type'] : 'post';
			
			if ( empty( $instance['title_tag'] ) ) {
				$instance['title_tag'] = 'h6';
			}
			
			$params = array(
				'post_type'      => $type,
				'post_status'    => 'publish',
				'order'          => 'DESC',
				'orderby'        => 'date',
				'posts_per_page' => 4
			);
			
			$query = new WP_Query( $params );
			
			$html = '<div class="widget edgtf-recent-post-widget" ' . overworld_edge_get_inline_style( $widget_styles ) . '>';
			
			if ( ! empty( $instance['widget_title'] ) ) {
				if ( ! empty( $widget_title_styles ) ) {
					$args['before_title'] = overworld_edge_widget_modified_before_title( $args['before_title'], $widget_title_styles );
				}
				
				$html .= wp_kses_post( $args['before_title'] ) . esc_html( $instance['widget_title'] ) . wp_kses_post( $args['after_title'] );
			}
			
			if ( $query->have_posts() ) {
				$html .= '<ul class="edgtf-recent-posts">';
				while ( $query->have_posts() ) {
					$query->the_post();
					$html .= '<li class="edgtf-rp-item"><a href="' . get_the_permalink() . '"><div class="edgtf-rp-image">' . get_the_post_thumbnail( get_the_ID(), array( 56, 56 ) ) . '</div><' . $instance['title_tag'] . ' class="edgtf-rp-title">' . get_the_title() . '</' . $instance['title_tag'] . '></a></li>';
				}
				$html .= '</ul>';
			} else {
				$html .= esc_html__( 'Sorry, there are no posts matching your criteria', 'overworld' );
			}
			
			$html .= '</div>';
			
			wp_reset_postdata();
			
			echo overworld_edge_get_module_part( $html );
		}
	}
}