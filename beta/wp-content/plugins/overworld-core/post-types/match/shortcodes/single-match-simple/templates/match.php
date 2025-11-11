<div class="edgtf-match-simple">
	<div class="edgtf-match-inner">
		<div class="edgtf-team-holder edgtf-team-1">
			<div class="edgtf-team-logo">
				<a itemprop="url" href="<?php echo get_the_permalink( $team_1 ); ?>" title="<?php the_title_attribute( array( 'post' => $team_1 ) ); ?>">
	                <?php echo get_the_post_thumbnail( $team_1 ); ?>
				</a>
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
			<div class="edgtf-match-date"> <?php echo esc_attr($date) ?>, <?php echo esc_attr($time) ?></div>
		</div>
		<div class="edgtf-team-holder edgtf-team-2">
			<div class="edgtf-team-logo">
				<a itemprop="url" href="<?php echo get_the_permalink( $team_2 ); ?>" title="<?php the_title_attribute( array( 'post' => $team_2 ) ); ?>">
	                <?php echo get_the_post_thumbnail( $team_2 ); ?>
				</a>
            </div>
		</div>
	</div>
</div>