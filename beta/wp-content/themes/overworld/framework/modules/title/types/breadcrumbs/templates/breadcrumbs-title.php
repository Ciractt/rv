<?php do_action('overworld_edge_action_before_page_title'); ?>

<div class="edgtf-title-holder <?php echo esc_attr($holder_classes); ?>" <?php overworld_edge_inline_style($holder_styles); ?> <?php echo overworld_edge_get_inline_attrs($holder_data); ?>>
	<?php if(!empty($title_image)) { ?>
		<div class="edgtf-title-image">
			<img itemprop="image" src="<?php echo esc_url($title_image['src']); ?>" alt="<?php echo esc_attr($title_image['alt']); ?>" />
		</div>
	<?php } ?>
	<div class="edgtf-title-wrapper" <?php overworld_edge_inline_style($wrapper_styles); ?>>
		<?php if(!isset($title_hide_content) || $title_hide_content !== 'yes') { ?>
		<div class="edgtf-title-inner">
			<div class="edgtf-grid">
				<?php overworld_edge_custom_breadcrumbs(); ?>
			</div>
	    </div>
		<?php } ?>
	</div>
</div>

<?php do_action('overworld_edge_action_after_page_title'); ?>
