<div class="edgtf-image-showcase <?php echo esc_attr($holder_classes); ?>">
	<div class="edgtf-is-slider edgtf-owl-slider" <?php echo overworld_edge_get_inline_attrs($slider_data); ?>>
		<?php foreach ($images as $image) {
			$image_styles = 'background-image: url(' . esc_url( $image['url'] ) .')'; ?>
			<div class="edgtf-is-image" <?php overworld_edge_inline_style($image_styles); ?>>
				<?php if(is_array($image_size) && count($image_size)) : ?>
	                <?php echo overworld_edge_generate_thumbnail($image['image_id'], null, $image_size[0], $image_size[1]); ?>
	            <?php else: ?>
	                <?php echo wp_get_attachment_image($image['image_id'], $image_size); ?>
	            <?php endif; ?>
			</div>
		<?php } ?>
	</div>
	<div class="edgtf-is-info">
		<div class="edgtf-is-info-inner" <?php echo overworld_edge_get_inline_style( $content_styles ); ?>>
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>
</div>