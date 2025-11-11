<div class="edgtf-player-personal edgtf-player-section">
	<div class="edgtf-player-nationality-holder edgtf-player-info-container">
		<div class="edgtf-player-nationality-info edgtf-player-info-content">
			<div class="edgtf-player-nationality-flag edgtf-player-info-icon">
				<?php $nationality_flag_id = overworld_edge_get_attachment_id_from_url( $nationality_flag );
				echo wp_get_attachment_image( $nationality_flag_id, 'full' ); ?>
			</div>
			<div class="edgtf-player-nationality edgtf-player-info-title">
				<?php echo esc_html($nationality); ?>
			</div>
		</div>
		<div class="edgtf-player-nationality-label edgtf-player-info-label"><?php echo esc_html__( 'Nationality', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-player-age-holder edgtf-player-info-container">
		<div class="edgtf-player-age-info edgtf-player-info-content">
			<div class="edgtf-player-age-icon ion-ios-person edgtf-player-info-icon"></div>
			<div class="edgtf-player-age edgtf-player-info-title">
				<?php $player_age = 'N/A';
				$birth_date_object = DateTime::createFromFormat( 'Y-m-d', $birth_date );
				if ( $birth_date_object ) {

					$today_object = new DateTime();
					if ($today_object >= $birth_date_object) {

						$age_object = $today_object->diff( $birth_date_object );

						$player_age = $age_object->y;
					}
				}
				echo esc_html($player_age); ?>
			</div>
		</div>
		<div class="edgtf-player-age-label edgtf-player-info-label"><?php echo esc_html__( 'Player Age', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-player-role-holder edgtf-player-info-container">
		<div class="edgtf-player-role-info edgtf-player-info-content">
			<div class="edgtf-player-role-icon ion-ios-game-controller-b edgtf-player-info-icon"></div>
			<div class="edgtf-player-role edgtf-player-info-title">
				<?php echo esc_html($role); ?>
			</div>
		</div>
		<div class="edgtf-player-role-label edgtf-player-info-label"><?php echo esc_html__( 'Player Role', 'overworld-core' ); ?></div>
	</div>
</div>