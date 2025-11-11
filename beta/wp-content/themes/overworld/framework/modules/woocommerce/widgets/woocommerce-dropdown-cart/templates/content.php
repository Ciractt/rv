<?php if ( is_object( WC()->cart ) ) { ?>
<?php overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/parts/opener', 'woocommerce' ); ?>
<div class="edgtf-sc-dropdown">
	<div class="edgtf-sc-dropdown-inner">
		<?php if ( ! WC()->cart->is_empty() ) {
			overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/parts/loop', 'woocommerce' );

			overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/parts/order-details', 'woocommerce' );

			overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/parts/button', 'woocommerce' );
		} else {
			overworld_edge_get_module_template_part( 'widgets/woocommerce-dropdown-cart/templates/posts-not-found', 'woocommerce' );
		} ?>
	</div>
</div>
<?php } ?>