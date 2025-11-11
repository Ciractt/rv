<?php
$ribbon_image = array(
	'background-image: url( ' . OVERWORLD_CORE_ASSETS_URL_PATH . '/css/img/ribbon.png )',
	'background-repeat: no-repeat',
	'background-position: top center',
	'background-size: cover'
);
?>
<div class="edgtf-match-single-info-top">
	<div class="edgtf-match-single-info-top-inner">
		<div class="edgtf-match-bg-ribbon" <?php overworld_edge_inline_style( $ribbon_image ); ?>>
			<?php
			$team_1_score_sign = 'ion-ios-star-outline';
			$team_2_score_sign = 'ion-ios-star-outline';
			$team_1_score_value = floatval($team_1_score);
			$team_2_score_value = floatval($team_2_score);
			if ($team_1_score_value > $team_2_score_value) {
				$team_1_score_sign = 'ion-ios-star edgtf-winner';
			} else if ($team_1_score_value < $team_2_score_value) {
				$team_2_score_sign = 'ion-ios-star edgtf-winner';
			}
			?>
			<span class="edgtf-decoration edgtf-decoration-left <?php echo esc_attr($team_1_score_sign); ?>"></span>
			<span class="edgtf-decoration edgtf-decoration-right <?php echo esc_attr($team_2_score_sign); ?>"></span>
		</div>
		<div class="edgtf-match-scoreboard">
			<div class="edgtf-match-team-logo edgtf-team-1-logo"><?php echo get_the_post_thumbnail( $team_1 ); ?></div>
			<div class="edgtf-match-score">
				<?php echo esc_html( $team_1_score ); ?>
				<span class="edgtf-match-score-separator">:</span>
				<?php echo esc_html( $team_2_score ); ?>
			</div>
			<div class="edgtf-match-team-logo edgtf-team-2-logo"><?php echo get_the_post_thumbnail( $team_2 ); ?></div>
		</div>
	</div>
</div>