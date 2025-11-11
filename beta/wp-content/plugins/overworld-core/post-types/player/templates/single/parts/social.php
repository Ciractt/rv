<?php if ( ! empty( $social_icons ) ) { ?>
	<div class="edgtf-player-social-holder edgtf-player-section">
		<span class="edgtf-player-social-title"><?php echo esc_html__( 'Follow:', 'overworld-core' ); ?></span>
		<span class="edgtf-player-social-icons">
			<?php foreach ( $social_icons as $social_icon ) { ?>
				<span class="edgtf-player-icon"><?php echo wp_kses_post( $social_icon ); ?></span>
			<?php } ?>
		</span>
	</div>
<?php } ?>