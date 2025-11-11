<div class="edgtf-team edgtf-item-space">
	<div class="edgtf-team-inner">
	    <?php echo overworld_core_get_cpt_shortcode_module_template_part('team', 'single-team', 'categories', '', $params); ?>
	    <?php if (get_the_post_thumbnail($team_id) !== '') { ?>
	        <div class="edgtf-team-image">
	            <?php echo get_the_post_thumbnail($team_id); ?>
	        </div>
	    <?php } ?>
		<div class="edgtf-team-info">
	        <h4 itemprop="name" class="edgtf-team-name entry-title"><?php echo esc_html(get_the_title($team_id)); ?></h4>
			<?php if ( ! empty( $team_social_icons ) ) { ?>
				<div class="edgtf-team-social-holder edgtf-team-section">
					<span class="edgtf-team-social-icons">
						<?php foreach ( $team_social_icons as $social_icon ) { ?>
							<span class="edgtf-team-icon"><?php echo wp_kses_post( $social_icon ); ?></span>
						<?php } ?>
					</span>
				</div>
			<?php } ?>
	    </div>
	    <a class="edgtf-team-overlay-link" itemprop="url" href="<?php echo esc_url(get_the_permalink($team_id)); ?>" title="<?php echo esc_attr(get_the_title($team_id)); ?>"></a>
	</div>
</div>