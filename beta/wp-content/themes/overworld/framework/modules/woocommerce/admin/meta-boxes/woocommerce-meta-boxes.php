<?php

if ( ! function_exists( 'overworld_edge_map_woocommerce_meta' ) ) {
	function overworld_edge_map_woocommerce_meta() {
		
		$woocommerce_meta_box = overworld_edge_create_meta_box(
			array(
				'scope' => array( 'product' ),
				'title' => esc_html__( 'Product Meta', 'overworld' ),
				'name'  => 'woo_product_meta'
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'        => 'edgtf_product_featured_image_size',
				'type'        => 'select',
				'label'       => esc_html__( 'Dimensions for Product List Shortcode', 'overworld' ),
				'description' => esc_html__( 'Choose image layout when it appears in Edge Product List - Masonry layout shortcode', 'overworld' ),
				'options'     => array(
					''                   => esc_html__( 'Default', 'overworld' ),
					'small'              => esc_html__( 'Small', 'overworld' ),
					'large-width'        => esc_html__( 'Large Width', 'overworld' ),
					'large-height'       => esc_html__( 'Large Height', 'overworld' ),
					'large-width-height' => esc_html__( 'Large Width Height', 'overworld' )
				),
				'parent'      => $woocommerce_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_show_title_area_woo_meta',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Show Title Area', 'overworld' ),
				'description'   => esc_html__( 'Disabling this option will turn off page title area', 'overworld' ),
				'options'       => overworld_edge_get_yes_no_select_array(),
				'parent'        => $woocommerce_meta_box
			)
		);
		
		overworld_edge_create_meta_box_field(
			array(
				'name'          => 'edgtf_show_new_sign_woo_meta',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Show New Sign', 'overworld' ),
				'description'   => esc_html__( 'Enabling this option will show new sign mark on product', 'overworld' ),
				'parent'        => $woocommerce_meta_box
			)
		);
	}
	
	add_action( 'overworld_edge_action_meta_boxes_map', 'overworld_edge_map_woocommerce_meta', 99 );
}