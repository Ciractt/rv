<?php do_action('overworld_edge_action_before_mobile_header'); ?>

<header class="edgtf-mobile-header">
	<?php do_action('overworld_edge_action_after_mobile_header_html_open'); ?>
	
	<div class="edgtf-mobile-header-inner">
		<div class="edgtf-mobile-header-holder">
			<?php if ($mobile_header_in_grid) : ?>
            <div class="edgtf-grid">
            <?php endif; ?>
                <div class="edgtf-vertical-align-containers">
                    <div class="edgtf-position-left"><!--
                     --><div class="edgtf-position-left-inner">
                            <?php overworld_edge_get_mobile_logo(); ?>
                        </div>
                    </div>
                    <div class="edgtf-position-right"><!--
                     --><div class="edgtf-position-right-inner">
                            <?php if ( is_active_sidebar( 'edgtf-right-from-mobile-logo' ) ) {
                                dynamic_sidebar( 'edgtf-right-from-mobile-logo' );
                            } ?>
                            <?php if ( $show_navigation_opener ) : ?>
                                <div <?php overworld_edge_class_attribute( $mobile_icon_class ); ?>>
                                    <a href="javascript:void(0)">
                                        <?php if ( ! empty( $mobile_menu_title ) ) { ?>
                                            <h5 class="edgtf-mobile-menu-text"><?php echo esc_attr( $mobile_menu_title ); ?></h5>
                                        <?php } ?>
                                        <span class="edgtf-mobile-menu-icon">
                                            <?php echo overworld_edge_get_icon_sources_html( 'mobile' ); ?>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
				</div>
            <?php if ($mobile_header_in_grid) : ?>
            </div>
		    <?php endif; ?>
		</div>
		<?php overworld_edge_get_mobile_nav(); ?>
	</div>
	
	<?php do_action('overworld_edge_action_before_mobile_header_html_close'); ?>
</header>

<?php do_action('overworld_edge_action_after_mobile_header'); ?>