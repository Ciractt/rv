<div class="edgtf-stream-list-holder edgtf-grid-list edgtf-disable-bottom-space <?php echo esc_attr( $holder_classes ); ?>">
	<div class="edgtf-sb-stream-wrapper edgtf-outer-space">
		<?php
		if ( ! empty( $stream_items ) ) {
			foreach ( $stream_items as $stream_item ) { ?>
				
				<div class="edgtf-sb-main-stream-item edgtf-item-space">
					<div class="edgtf-sb-main-stream-holder">
						
						<div class="edgtf-sb-main-stream-image">
							<?php echo wp_get_attachment_image( $stream_item['stream_background_image'], 'full' ); ?>
						</div>
						
						<div class="edgtf-sb-text-holder">
							<?php if ( ! empty( $stream_item['stream_platform'] ) ) { ?>
								<div class="edgtf-sb-platform"><?php echo esc_html( $stream_item['stream_platform'] ); ?></div>
							<?php } ?>

							<?php if ( ! empty( $stream_item['stream_channel'] ) ) { ?>
								<div class="edgtf-sb-channel"><?php echo esc_html( $stream_item['stream_channel'] ); ?></div>
							<?php } ?>

							<?php if ( ! empty( $stream_item['stream_title'] ) ) { ?>
								<<?php echo esc_attr($title_tag); ?> class="edgtf-sb-title">
									<?php echo esc_html( $stream_item['stream_title'] ); ?>
								</<?php echo esc_attr($title_tag); ?>>
							<?php } ?>
						</div>

						<?php if ( ! empty( $stream_item['stream_link'] ) ) { ?>
							<a class="edgtf-sb-link" href="<?php echo esc_url( $stream_item['stream_link'] ); ?>" target="_blank">
								<span class="edgtf-video-button-play" <?php overworld_edge_inline_style( $this_object->getStreamLinkStyles( $stream_item ) ); ?>>
									<span class="edgtf-video-button-play-inner">
										<i class="ion-ios-play-outline"></i>
									</span>
								</span>
							</a>
						<?php } ?>

					</div>
				</div>
			<?php }
		} ?>
	</div>
</div>