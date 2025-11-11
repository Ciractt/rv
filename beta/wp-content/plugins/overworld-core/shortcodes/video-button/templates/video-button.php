<?php
$rand = rand(0, 1000);
$link_class = !empty($play_button_hover_image) ? 'edgtf-vb-has-hover-image' : '';
?>
<div class="edgtf-video-button-holder <?php echo esc_attr($holder_classes); ?>">
	<div class="edgtf-video-button-image">
		<?php echo wp_get_attachment_image($video_image, 'full'); ?>
	</div>
	<?php if(!empty($play_button_image)) { ?>
		<a class="edgtf-video-button-play-image <?php echo esc_attr($link_class); ?>" href="<?php echo esc_url($video_link); ?>" data-rel="prettyPhoto[video_button_pretty_photo_<?php echo esc_attr($rand); ?>]">
			<span class="edgtf-video-button-play-inner">
				<?php echo wp_get_attachment_image($play_button_image, 'full'); ?>
				<?php if(!empty($play_button_hover_image)) { ?>
					<?php echo wp_get_attachment_image($play_button_hover_image, 'full'); ?>
				<?php } ?>
			</span>
		</a>
	<?php } else { ?>
		<a class="edgtf-video-button-play" href="<?php echo esc_url($video_link); ?>" data-rel="prettyPhoto[video_button_pretty_photo_<?php echo esc_attr($rand); ?>]">
			<span class="edgtf-video-button-play-inner" <?php echo overworld_edge_get_inline_style($play_button_styles); ?>>
				<span class="edgtf-video-button-play-inner-2">
					<span class="edgtf-video-button-play-icon ion-ios-play-outline" <?php echo overworld_edge_get_inline_style($play_button_inner_styles); ?>></span>
				</span>
			</span>
		</a>
	<?php } ?>
</div>