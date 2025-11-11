<div class="edgtf-sc-dropdown-button-holder">
	<a itemprop="url" href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="edgtf-sc-dropdown-button edgtf-btn edgtf-btn-medium edgtf-btn-simple edgtf-btn-icon edgtf-btn-icon-left">
        <i class="edgtf-icon-ion-icon ion-android-arrow-forward "></i>
        <span class="edgtf-btn-text"><?php esc_html_e( 'Cart', 'overworld' ); ?></span>
    </a>
    <a itemprop="url" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="edgtf-sc-dropdown-button edgtf-btn edgtf-btn-medium edgtf-btn-simple edgtf-btn-icon">
        <span class="edgtf-btn-text"><?php esc_html_e( 'Checkout', 'overworld' ); ?></span>
        <i class="edgtf-icon-ion-icon ion-android-arrow-forward "></i>
    </a>
</div>