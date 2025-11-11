<?php do_action('overworld_edge_action_before_page_header'); ?>

<aside class="edgtf-vertical-menu-area <?php echo esc_attr($holder_class); ?>">
	<div class="edgtf-vertical-menu-area-inner">
		<div class="edgtf-vertical-area-background"></div>
		<?php if(!$hide_logo) {
			overworld_edge_get_logo();
		} ?>
		<?php if ( overworld_edge_is_header_widget_area_active( 'one' ) ) { ?>
            <div class="edgtf-vertical-area-top-widget-holder">
				<?php overworld_edge_get_header_widget_area_one(); ?>
            </div>
		<?php } ?>
		<?php overworld_edge_get_header_vertical_main_menu(); ?>
		<?php if ( overworld_edge_is_header_widget_area_active( 'two' ) ) { ?>
			<div class="edgtf-vertical-area-bottom-widget-holder">
				<?php overworld_edge_get_header_widget_area_two(); ?>
			</div>
		<?php } ?>
	</div>
</aside>

<?php do_action('overworld_edge_action_after_page_header'); ?>