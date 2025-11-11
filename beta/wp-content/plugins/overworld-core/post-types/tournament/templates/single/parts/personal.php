<div class="edgtf-tournament-personal edgtf-tournament-section">
	<div class="edgtf-tournament-prize-pool-holder edgtf-tournament-info-container">
		<div class="edgtf-tournament-prize-pool-info edgtf-tournament-info-content">
			<div class="edgtf-tournament-prize-pool-icon ion-cash edgtf-tournament-info-icon"></div>
			<div class="edgtf-tournament-prize-pool edgtf-tournament-info-title">
				<?php echo esc_html($prize_pool); ?>
			</div>
		</div>
		<div class="edgtf-tournament-prize-pool-label edgtf-tournament-info-label"><?php echo esc_html__( 'Prize Pool', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-tournament-play-mode-holder edgtf-tournament-info-container">
		<div class="edgtf-tournament-play-mode-info edgtf-tournament-info-content">
			<div class="edgtf-tournament-play-mode-icon ion-ios-game-controller-b edgtf-tournament-info-icon"></div>
			<div class="edgtf-tournament-play-mode edgtf-tournament-info-title">
				<?php echo esc_html($play_mode); ?>
			</div>
		</div>
		<div class="edgtf-tournament-play-mode-label edgtf-tournament-info-label"><?php echo esc_html__( 'Play Mode', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-tournament-platform-holder edgtf-tournament-info-container">
		<div class="edgtf-tournament-platform-info edgtf-tournament-info-content">
			<div class="edgtf-tournament-platform-icon edgtf-tournament-info-icon">
				<?php $platform_logo_id = overworld_edge_get_attachment_id_from_url( $platform_logo );
				echo wp_get_attachment_image( $platform_logo_id, 'full' ); ?>
			</div>
			<div class="edgtf-tournament-platform edgtf-tournament-info-title">
				<?php echo esc_html($platform); ?>
			</div>
		</div>
		<div class="edgtf-tournament-platform-label edgtf-tournament-info-label"><?php echo esc_html__( 'Platform', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-tournament-players-holder edgtf-tournament-info-container">
		<div class="edgtf-tournament-players-info edgtf-tournament-info-content">
			<div class="edgtf-tournament-players-icon ion-android-people edgtf-tournament-info-icon"></div>
			<div class="edgtf-tournament-players edgtf-tournament-info-title">
				<?php echo esc_html($players_number); ?>
			</div>
		</div>
		<div class="edgtf-tournament-players-label edgtf-tournament-info-label"><?php echo esc_html__( 'Players', 'overworld-core' ); ?></div>
	</div>
</div>