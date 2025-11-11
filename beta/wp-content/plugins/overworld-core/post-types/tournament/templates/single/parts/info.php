<div class="edgtf-tournament-info-holder">
	<div class="edgtf-tournament-info-main">
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/sponsor', 'tournament', '', $params); ?>
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/date-time-location', 'tournament', '', $params); ?>
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/personal', 'tournament', '', $params); ?>
	</div>
	<div class="edgtf-tournament-info-description">
		<?php echo overworld_edge_get_section_title_html( array(
			'title'      => esc_html__( 'About The Tournament', 'overworld-core' ),
			'title_type' => 'simple-decorated',
			'title_tag'  => 'h3'
		) ); ?>
		<div class="edgtf-tournament-description-content"><?php the_excerpt() ?></div>
	</div>
</div>