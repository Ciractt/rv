<?php $team_stats = overworld_core_calculate_team_stats($team_id); ?>
<div class="edgtf-team-personal edgtf-team-section">
	<div class="edgtf-team-wins-holder edgtf-team-info-container">
		<div class="edgtf-team-wins-info edgtf-team-info-content">
			<div class="edgtf-team-wins edgtf-team-info-title">
				<?php echo esc_html($team_stats['wins']); ?>
			</div>
		</div>
		<div class="edgtf-team-wins-label edgtf-team-info-label"><?php echo esc_html__( 'Wins', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-team-loses-holder edgtf-team-info-container">
		<div class="edgtf-team-loses-info edgtf-team-info-content">
			<div class="edgtf-team-loses edgtf-team-info-title">
				<?php echo esc_html($team_stats['loses']); ?>
			</div>
		</div>
		<div class="edgtf-team-loses-label edgtf-team-info-label"><?php echo esc_html__( 'Loses', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-team-draws-holder edgtf-team-info-container">
		<div class="edgtf-team-draws-info edgtf-team-info-content">
			<div class="edgtf-team-draws edgtf-team-info-title">
				<?php echo esc_html($team_stats['draws']); ?>
			</div>
		</div>
		<div class="edgtf-team-draws-label edgtf-team-info-label"><?php echo esc_html__( 'Draws', 'overworld-core' ); ?></div>
	</div>

	<div class="edgtf-team-plays-holder edgtf-team-info-container">
		<div class="edgtf-team-plays-info edgtf-team-info-content">
			<div class="edgtf-team-plays edgtf-team-info-title">
				<?php echo esc_html($team_stats['total']); ?>
			</div>
		</div>
		<div class="edgtf-team-plays-label edgtf-team-info-label"><?php echo esc_html__( 'Plays', 'overworld-core' ); ?></div>
	</div>
</div>