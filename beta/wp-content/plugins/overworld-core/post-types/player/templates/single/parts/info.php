<div class="edgtf-player-info-holder">
	<div class="edgtf-player-info-main">
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/team', 'player', '', $params); ?>
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/social', 'player', '', $params); ?>
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/personal', 'player', '', $params); ?>
	</div>
	<div class="edgtf-player-info-description">
		<?php echo overworld_edge_get_section_title_html( array(
			'title'      => esc_html__( 'About The Player', 'overworld-core' ),
			'title_type' => 'simple-decorated',
			'title_tag'  => 'h3'
		) ); ?>
		<div class="edgtf-player-description-content"><?php the_excerpt() ?></div>
	</div>
</div>