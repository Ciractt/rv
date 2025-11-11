<?php
$team_1_score_sign = 'ion-ios-star-outline';
$team_2_score_sign = 'ion-ios-star-outline';
$team_1_score_value = floatval($team_1_score);
$team_2_score_value = floatval($team_2_score);
$match              = $match_id;
if ($team_1_score_value > $team_2_score_value) {
	$team_1_score_sign = 'ion-ios-star edgtf-winner';
} else if ($team_1_score_value < $team_2_score_value) {
	$team_2_score_sign = 'ion-ios-star edgtf-winner';
}
?>
<div class="edgtf-match">
	<div class="edgtf-match-inner">
		<div class="edgtf-team-holder edgtf-team-1">
			<div class="edgtf-team-logo">
				<a itemprop="url" href="<?php echo get_the_permalink( $team_1 ); ?>" title="<?php the_title_attribute( array( 'post' => $team_1 ) ); ?>">
	                <?php echo get_the_post_thumbnail( $team_1 ); ?>
				</a>
            </div>
			<div class="edgtf-match-bg-ribbon">
				<div class="edgtf-match-bg-ribbon-inner">
					<span class="edgtf-decoration <?php echo esc_attr($team_1_score_sign); ?>"></span>
					<div class="edgtf-team-main">
			            <div class="edgtf-team-name-holder">
				            <<?php echo esc_attr($title_tag); ?> itemprop="name" class="edgtf-team-name entry-title">
					            <a itemprop="url" href="<?php echo get_the_permalink( $team_1 ); ?>" title="<?php the_title_attribute( array( 'post' => $team_1 ) ); ?>">
				                    <?php echo get_the_title( $team_1 ); ?>
				                </a>
			                </<?php echo esc_attr($title_tag); ?>>
			            </div>
					</div>
				</div>
			</div>
            <div class="edgtf-team-meta">
	            <span class="edgtf-stream-link"><?php echo esc_html__('Watch', 'overworld-core'); ?></span>
	            <?php if ( ! empty( $team_1_special_links ) ) { ?>
					<div class="edgtf-team-social-holder edgtf-team-section">
						<span class="edgtf-team-social-icons">
							<?php foreach ( $team_1_special_links as $special_link ) { ?>
								<span class="edgtf-team-icon"><?php echo wp_kses_post( $special_link ); ?></span>
							<?php } ?>
						</span>
					</div>
				<?php } ?>
            </div>
		</div>
	
		<div class="edgtf-match-board">
			<div class="edgtf-match-score">
				<?php echo esc_html( $team_1_score ); ?>
				<span class="edgtf-match-score-separator">:</span>
				<?php echo esc_html( $team_2_score ); ?>
			</div>
			<?php $dateobj = date_create_from_format('Y-m-d', $date);
			$date = '';
			if ($dateobj) {
				$date = date_format($dateobj, 'jS F Y');
			} ?>
			<div class="edgtf-match-date">
				<a itemprop="url" href="<?php echo get_the_permalink( $match ); ?>" title="<?php the_title_attribute( array( 'post' => $match ) ); ?>">
					<?php echo esc_attr($date) ?>, <?php echo esc_attr($time) ?>
				</a>
			</div>
		</div>
	
		<div class="edgtf-team-holder edgtf-team-2">
			<div class="edgtf-team-logo">
				<a itemprop="url" href="<?php echo get_the_permalink( $team_2 ); ?>" title="<?php the_title_attribute( array( 'post' => $team_2 ) ); ?>">
	                <?php echo get_the_post_thumbnail( $team_2 ); ?>
				</a>
            </div>
			<div class="edgtf-match-bg-ribbon">
				<div class="edgtf-match-bg-ribbon-inner">
					<span class="edgtf-decoration <?php echo esc_attr($team_2_score_sign); ?>"></span>
					<div class="edgtf-team-main">
			            <div class="edgtf-team-name-holder">
				            <<?php echo esc_attr($title_tag); ?> itemprop="name" class="edgtf-team-name entry-title">
	                            <a itemprop="url" href="<?php echo get_the_permalink( $team_2 ); ?>" title="<?php the_title_attribute( array( 'post' => $team_2 ) ); ?>">
				                    <?php echo get_the_title( $team_2 ); ?>
	                            </a>
			                </<?php echo esc_attr($title_tag); ?>>
			            </div>
					</div>
				</div>
			</div>
            <div class="edgtf-team-meta">
	            <?php if ( ! empty( $team_2_special_links ) ) { ?>
					<div class="edgtf-team-social-holder edgtf-team-section">
						<span class="edgtf-team-social-icons">
							<?php foreach ( $team_2_special_links as $special_link ) { ?>
								<span class="edgtf-team-icon"><?php echo wp_kses_post( $special_link ); ?></span>
							<?php } ?>
						</span>
					</div>
				<?php } ?>
	            <span class="edgtf-stream-link"><?php echo esc_html__('Watch', 'overworld-core'); ?></span>
            </div>
		</div>
	</div>
</div>