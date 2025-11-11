<div class="edgtf-stream-box-holder <?php echo esc_attr( $holder_classes ); ?>">
	<?php
	$main_bgrnd_img_style = '';
	if ( ! empty( $main_stream_image ) ) {
		$main_bgrnd_img_style .= "background-image: url(" . wp_get_attachment_url( $main_stream_image ) . ");";
	}
	?>
	<div class="edgtf-sb-main-stream-item">
		<div class="edgtf-sb-main-stream-holder">
			<a class="edgtf-sb-link" href="<?php echo esc_url( $main_stream_link ); ?>" target="_blank"></a>
			<div class="edgtf-sb-main-image">
				<?php echo wp_get_attachment_image( $main_stream_image, 'full' ); ?>
			</div>
			<a class="edgtf-video-button-play" href="<?php echo esc_url( $main_stream_link ); ?>" target="_blank">
				<span class="edgtf-video-button-play-inner">
					<i class="ion-ios-play-outline"></i>
				</span>
			</a>
			<div class="edgtf-sb-text-holder">
				<?php if ( ! empty( $main_stream_platform ) ) { ?>
					<div class="edgtf-sb-platform"><?php echo esc_html( $main_stream_platform ); ?></div>
				<?php } ?>
				<?php if ( ! empty( $main_stream_channel ) ) { ?>
					<div class="edgtf-sb-channel"><?php echo esc_html( $main_stream_channel ); ?></div>
				<?php } ?>
				<?php if ( ! empty( $main_stream_title ) ) { ?>
					<h3 class="edgtf-sb-title">
						<a href="<?php echo esc_url( $main_stream_link ); ?>" target="_blank">
							<?php echo esc_html( $main_stream_title ); ?>
						</a>
					</h3>
				<?php } ?>
			</div>
		</div>
		<div class="edgtf-stream-bgrnd" <?php echo overworld_edge_get_inline_style( $main_bgrnd_img_style ); ?>></div>
	</div>
	<?php foreach ( $single_stream as $single_stream_item ) { ?>
		<?php
		$bgrnd_img_style = '';
		
		if ( ! empty( $single_stream_item['stream_background_image'] ) ) {
			$bgrnd_img_style .= "background-image: url(" . wp_get_attachment_url( $single_stream_item['stream_background_image'] ) . ");";
		}
		?>
		<div class="edgtf-sb-bottom-stream-item">
			<div class="edgtf-sb-bottom-stream-holder">
				<?php if ( $single_stream_item['stream_link'] !== '' ) { ?>
					<a class="edgtf-sb-link" href="<?php echo esc_url( $single_stream_item['stream_link'] ); ?>" target="_blank"></a>
				<?php } ?>
				<div class="edgtf-sb-bottom-stream-image">
					<?php echo wp_get_attachment_image( $single_stream_item['stream_background_image'], 'full' ); ?>
				</div>
				<a class="edgtf-video-button-play" href="<?php echo esc_url( $single_stream_item['stream_link'] ); ?>" target="_blank">
					<span class="edgtf-video-button-play-inner">
						<i class="ion-ios-play-outline"></i>
					</span>
				</a>
				<div class="edgtf-sb-text-holder">
					<?php if ( $single_stream_item['stream_platform'] !== '' ) { ?>
						<div class="edgtf-sb-platform"><?php echo esc_html( $single_stream_item['stream_platform'] ); ?></div>
					<?php } ?>
					
					<?php if ( $single_stream_item['stream_channel'] !== '' ) { ?>
						<div class="edgtf-sb-channel"><?php echo esc_html( $single_stream_item['stream_channel'] ); ?></div>
					<?php } ?>
					
					<?php if ( $single_stream_item['stream_title'] !== '' ) { ?>
						<h3 class="edgtf-sb-title">
							<a href="<?php echo esc_url( $single_stream_item['stream_link'] ); ?>" target="_blank">
								<?php echo esc_html( $single_stream_item['stream_title'] ); ?>
							</a>
						</h3>
					<?php } ?>
				</div>
			</div>
			<div class="edgtf-stream-bgrnd" <?php echo overworld_edge_get_inline_style( $bgrnd_img_style ); ?>></div>
		</div>
	<?php } ?>
</div>