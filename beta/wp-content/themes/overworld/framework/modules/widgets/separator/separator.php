<?php

if ( class_exists( 'OverworldCoreClassWidget' ) ) {
	class OverworldEdgeClassSeparatorWidget extends OverworldCoreClassWidget {
		public function __construct() {
			parent::__construct(
				'edgtf_separator_widget',
				esc_html__( 'Overworld Separator Widget', 'overworld' ),
				array( 'description' => esc_html__( 'Add a separator element to your widget areas', 'overworld' ) )
			);
			
			$this->setParams();
		}
		
		protected function setParams() {
			$this->params = array(
				array(
					'type'    => 'dropdown',
					'name'    => 'type',
					'title'   => esc_html__( 'Type', 'overworld' ),
					'options' => array(
						'normal'     => esc_html__( 'Normal', 'overworld' ),
						'full-width' => esc_html__( 'Full Width', 'overworld' )
					)
				),
				array(
					'type'    => 'dropdown',
					'name'    => 'position',
					'title'   => esc_html__( 'Position', 'overworld' ),
					'options' => array(
						'center' => esc_html__( 'Center', 'overworld' ),
						'left'   => esc_html__( 'Left', 'overworld' ),
						'right'  => esc_html__( 'Right', 'overworld' )
					)
				),
				array(
					'type'    => 'dropdown',
					'name'    => 'border_style',
					'title'   => esc_html__( 'Style', 'overworld' ),
					'options' => array(
						'solid'  => esc_html__( 'Solid', 'overworld' ),
						'dashed' => esc_html__( 'Dashed', 'overworld' ),
						'dotted' => esc_html__( 'Dotted', 'overworld' )
					)
				),
				array(
					'type'  => 'colorpicker',
					'name'  => 'color',
					'title' => esc_html__( 'Color', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'width',
					'title' => esc_html__( 'Width (px or %)', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'thickness',
					'title' => esc_html__( 'Thickness (px)', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'top_margin',
					'title' => esc_html__( 'Top Margin (px or %)', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'bottom_margin',
					'title' => esc_html__( 'Bottom Margin (px or %)', 'overworld' )
				)
			);
		}
		
		public function widget( $args, $instance ) {
			if ( ! is_array( $instance ) ) {
				$instance = array();
			}
			
			//prepare variables
			$params = '';
			
			//is instance empty?
			if ( is_array( $instance ) && count( $instance ) ) {
				//generate shortcode params
				foreach ( $instance as $key => $value ) {
					$params .= " $key='$value' ";
				}
			}
			
			echo '<div class="widget edgtf-separator-widget">';
			echo do_shortcode( "[edgtf_separator $params]" ); // XSS OK
			echo '</div>';
		}
	}
}