<div class="edgtf-sc-dropdown-items">
	<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		
		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>
			<div class="edgtf-sc-dropdown-item">
				<div class="edgtf-sc-dropdown-item-image">
					<?php
					$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					
					if ( ! $product_permalink ) {
						echo wp_kses_post( $thumbnail );
					} else {
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
					}?>
				</div>
				<div class="edgtf-sc-dropdown-item-content">
					<h5 itemprop="name" class="edgtf-sc-dropdown-item-title entry-title">
						<?php if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						} ?>
					</h5>
                    <p class="edgtf-sc-dropdown-item-quantity"><?php echo sprintf( esc_html__( '%s', 'overworld' ), esc_attr( $cart_item['quantity'] ) ); ?></p>
                    <span class="edgtf-sc-dropdown-item-separator">x</span>
                    <p class="edgtf-sc-dropdown-item-price"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></p>
					<?php echo sprintf( '<a href="%s" class="edgtf-sc-dropdown-item-remove remove" title="%s">%s</span></a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), esc_attr__( 'Remove this item', 'overworld' ),
'<svg 
 xmlns="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 width="13px" height="13px">
<path fill-rule="evenodd" fill="currentColor"
 d="M10.243,8.828 L8.828,10.242 L6.000,7.414 L3.171,10.242 L1.757,8.828 L4.586,6.000 L1.757,3.171 L3.172,1.757 L6.000,4.585 L8.828,1.757 L10.243,3.171 L7.414,5.999 L10.243,8.828 Z"/>
</svg>' ); ?>
				</div>
			</div>
		<?php }
	} ?>
</div>