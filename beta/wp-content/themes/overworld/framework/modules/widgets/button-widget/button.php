<?php

if ( class_exists( 'OverworldCoreClassWidget' ) ) {
	class OverworldEdgeClassButtonWidget extends OverworldCoreClassWidget {
		public function __construct() {
			parent::__construct(
				'edgtf_button_widget',
				esc_html__( 'Overworld Button Widget', 'overworld' ),
				array( 'description' => esc_html__( 'Add button element to widget areas', 'overworld' ) )
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
						'solid'   => esc_html__( 'Solid', 'overworld' ),
						'outline' => esc_html__( 'Outline', 'overworld' ),
						'simple'  => esc_html__( 'Simple', 'overworld' )
					)
				),
				array(
					'type'        => 'dropdown',
					'name'        => 'size',
					'title'       => esc_html__( 'Size', 'overworld' ),
					'options'     => array(
						'small'  => esc_html__( 'Small', 'overworld' ),
						'medium' => esc_html__( 'Medium', 'overworld' ),
						'large'  => esc_html__( 'Large', 'overworld' ),
						'huge'   => esc_html__( 'Huge', 'overworld' )
					),
					'description' => esc_html__( 'This option is only available for solid and outline button type', 'overworld' )
				),
				array(
					'type'    => 'textfield',
					'name'    => 'text',
					'title'   => esc_html__( 'Text', 'overworld' ),
					'default' => esc_html__( 'Button Text', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'link',
					'title' => esc_html__( 'Link', 'overworld' )
				),
				array(
					'type'    => 'dropdown',
					'name'    => 'target',
					'title'   => esc_html__( 'Link Target', 'overworld' ),
					'options' => overworld_edge_get_link_target_array()
				),
				array(
					'type'  => 'colorpicker',
					'name'  => 'color',
					'title' => esc_html__( 'Color', 'overworld' )
				),
				array(
					'type'  => 'colorpicker',
					'name'  => 'hover_color',
					'title' => esc_html__( 'Hover Color', 'overworld' )
				),
				array(
					'type'        => 'colorpicker',
					'name'        => 'background_color',
					'title'       => esc_html__( 'Background Color', 'overworld' ),
					'description' => esc_html__( 'This option is only available for solid button type', 'overworld' )
				),
				array(
					'type'        => 'colorpicker',
					'name'        => 'hover_background_color',
					'title'       => esc_html__( 'Hover Background Color', 'overworld' ),
					'description' => esc_html__( 'This option is only available for solid button type', 'overworld' )
				),
				array(
					'type'        => 'textfield',
					'name'        => 'padding',
					'title'       => esc_html__( 'Padding', 'overworld' ),
					'description' => esc_html__( 'Insert padding in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'overworld' )
				),
				array(
					'type'        => 'textfield',
					'name'        => 'margin',
					'title'       => esc_html__( 'Margin', 'overworld' ),
					'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'overworld' )
				)
			);
		}
		
		public function widget( $args, $instance ) {
			$params = '';
			
			if ( ! is_array( $instance ) ) {
				$instance = array();
			}
			
			// Filter out all empty params
			$instance = array_filter( $instance, function ( $array_value ) {
				return trim( $array_value ) != '';
			} );
			
			// Default values
			if ( ! isset( $instance['text'] ) ) {
				$instance['text'] = 'Button Text';
			}
			
			// Generate shortcode params
			foreach ( $instance as $key => $value ) {
				$params .= " $key='$value' ";
			}
			
			echo '<div class="widget edgtf-button-widget">';
			echo do_shortcode( "[edgtf_button $params]" ); // XSS OK
			echo '</div>';
		}
	}
}