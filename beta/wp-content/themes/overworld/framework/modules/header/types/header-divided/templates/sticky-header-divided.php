<?php do_action('overworld_edge_action_before_sticky_header'); ?>

    <div class="edgtf-sticky-header">

        <?php do_action('overworld_edge_action_after_sticky_menu_html_open'); ?>
        <div class="edgtf-sticky-holder">
            <?php if ($sticky_header_in_grid) : ?>
            <div class="edgtf-grid">
                <?php endif; ?>
                <div class="edgtf-vertical-align-containers">
                    <div class="edgtf-position-left"><!--
                     --><div class="edgtf-divided-left-widget-area">
		                    <div class="edgtf-divided-left-widget-area-inner">
			                    <div class="edgtf-position-left-inner-wrap">
		                            <?php overworld_edge_get_header_widget_area_one(); ?>
			                    </div>
			                </div>
			            </div><div class="edgtf-position-left-inner">
                            <?php overworld_edge_get_sticky_divided_left_main_menu('edgtf-sticky-nav'); ?>
                        </div>
                    </div>
                    <div class="edgtf-position-center"><!--
                     --><div class="edgtf-position-center-inner">
                            <?php if (!$hide_logo) {
                                overworld_edge_get_logo('sticky');
                            } ?>
                        </div>
                    </div>
                    <div class="edgtf-position-right"><!--
                     --><div class="edgtf-position-right-inner">
                            <?php overworld_edge_get_sticky_divided_right_main_menu('edgtf-sticky-nav'); ?>
                        </div>
	                    <div class="edgtf-divided-right-widget-area">
				            <div class="edgtf-divided-right-widget-area-inner">
					            <div class="edgtf-position-right-inner-wrap">
						            <?php overworld_edge_get_header_widget_area_two(); ?>
					            </div>
				            </div>
			            </div>
                    </div>
                </div>
                <?php if ($sticky_header_in_grid) : ?>
            </div>
        <?php endif; ?>
        </div>
    </div>

<?php do_action('overworld_edge_action_after_sticky_header'); ?>