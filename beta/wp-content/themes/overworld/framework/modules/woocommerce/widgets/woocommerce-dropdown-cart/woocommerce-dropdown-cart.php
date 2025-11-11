<?php

if ( class_exists( 'OverworldCoreClassWidget' ) ) {
	class OverworldEdgeClassWoocommerceDropdownCart extends OverworldCoreClassWidget {
		public function __construct() {
			parent::__construct(
				'edgtf_woocommerce_dropdown_cart',
				esc_html__('Overworld Woocommerce Dropdown Cart', 'overworld'),
				array('description' => esc_html__('Display a shop cart icon with a dropdown that shows products that are in the cart', 'overworld'),)
			);
			
			$this->setParams();
		}
		
		protected function setParams() {
			$this->params = array(
				array(
					'type'        => 'textfield',
					'name'        => 'woocommerce_dropdown_cart_margin',
					'title'       => esc_html__('Icon Margin', 'overworld'),
					'description' => esc_html__('Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'overworld')
				)
			);
		}
		
		public function widget( $args, $instance ) {
			$icon_styles = array();
			
			if ( $instance['woocommerce_dropdown_cart_margin'] !== '' ) {
				$icon_styles[] = 'margin: ' . $instance['woocommerce_dropdown_cart_margin'];
			}
			?>
			<div class="edgtf-shopping-cart-holder" <?php overworld_edge_inline_style( $icon_styles ) ?>>
				<div class="edgtf-shopping-cart-inner">
					<?php overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/content', 'woocommerce' ); ?>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'overworld_edge_woocommerce_header_add_to_cart_fragment' ) ) {
	function overworld_edge_woocommerce_header_add_to_cart_fragment( $fragments ) {
		ob_start();
		?>
		<div class="edgtf-shopping-cart-inner">
			<?php overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/content', 'woocommerce' ); ?>
		</div>
		
		<?php
		$fragments['div.edgtf-shopping-cart-inner'] = ob_get_clean();
		
		return $fragments;
	}
	
	add_filter( 'woocommerce_add_to_cart_fragments', 'overworld_edge_woocommerce_header_add_to_cart_fragment' );
}
?>