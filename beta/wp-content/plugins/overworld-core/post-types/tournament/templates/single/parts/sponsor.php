<?php if ( ! empty( $sponsor ) || ! empty( $sponsor_logo ) ) { ?>
	<div class="edgtf-tournament-sponsor edgtf-tournament-section">
		<?php if ( ! empty( $sponsor_logo ) ) { ?>
			<div class="edgtf-tournament-sponsor-thumb">
				<?php $sponsor_logo_id = overworld_edge_get_attachment_id_from_url( $sponsor_logo );
				echo wp_get_attachment_image( $sponsor_logo_id, 'full' ); ?>
			</div>
		<?php } ?>
		<div class="edgtf-tournament-sponsor-meta">
			<div class="edgtf-tournament-sponsor-name">
				<?php echo esc_html( $sponsor ); ?>
			</div>
		</div>
	</div>
<?php } ?>