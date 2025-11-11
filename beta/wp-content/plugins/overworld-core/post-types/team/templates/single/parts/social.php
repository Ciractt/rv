<?php if ( ! empty( $social_icons ) ) { ?>
	<div class="edgtf-team-social-holder edgtf-team-section">
		<span class="edgtf-team-social-title"><?php echo esc_html__( 'Follow:', 'overworld-core' ); ?></span>
		<span class="edgtf-team-social-icons">
			<?php foreach ( $social_icons as $social_icon ) { ?>
				<span class="edgtf-team-icon"><?php echo wp_kses_post( $social_icon ); ?></span>
			<?php } ?>
		</span>
	</div>
<?php } ?>