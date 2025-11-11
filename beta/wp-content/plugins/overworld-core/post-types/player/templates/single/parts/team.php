<?php if ( ! empty( $team ) ) {
	$team_meta = array(
		'title' => get_the_title( $team ),
		'url'   => get_post_permalink( $team )
	); ?>
	<div class="edgtf-player-team edgtf-player-section">
		<div class="edgtf-player-team-thumb">
			<a itemprop="url" href="<?php echo esc_url( $team_meta['url'] ); ?>" title="<?php echo esc_attr( $team_meta['title'] ); ?>">
				<?php echo get_the_post_thumbnail( $team ); ?>
			</a>
		</div>
		<div class="edgtf-player-team-meta">
			<div class="edgtf-player-team-name">
				<a itemprop="url" href="<?php echo esc_url( $team_meta['url'] ); ?>" title="<?php echo esc_attr( $team_meta['title'] ); ?>">
					<?php echo esc_html( $team_meta['title'] ); ?>
				</a>
			</div>
			<div class="edgtf-player-team-label edgtf-player-info-label"><?php echo esc_html__( 'Team', 'overworld-core' ); ?></div>
		</div>
	</div>
<?php } ?>