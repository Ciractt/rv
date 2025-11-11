<div class="edgtf-team-info-holder">
	<div class="edgtf-team-info-main">
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/sponsor', 'team', '', $params); ?>
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/social', 'team', '', $params); ?>
		<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/personal', 'team', '', $params); ?>
	</div>
	<div class="edgtf-team-info-description">
		<?php echo overworld_edge_get_section_title_html( array(
			'title'      => esc_html__( 'About The Team', 'overworld-core' ),
			'title_type' => 'simple-decorated',
			'title_tag'  => 'h3'
		) ); ?>
		<div class="edgtf-team-description-content"><?php the_excerpt() ?></div>
	</div>
</div>