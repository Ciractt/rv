<?php
$edgtf_grid_space_meta = overworld_edge_get_meta_field_intersect( 'page_grid_space' );
$edgtf_holder_classes  = ! empty( $edgtf_grid_space_meta ) ? 'edgtf-grid-' . $edgtf_grid_space_meta . '-gutter' : '';

if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php if(post_password_required()) {
		echo get_the_password_form();
	} else { ?>
		<div class="edgtf-team-single-holder">
			<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/top', 'team', '', $params); ?>
			<div class="edgtf-grid">
				<div class="edgtf-grid-row <?php echo esc_attr( $edgtf_holder_classes ); ?>">
					<div <?php echo overworld_edge_get_content_sidebar_class(); ?>>
						<div class="edgtf-team-single-outer">
							<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/info', 'team', '', $params); ?>
							<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/content', 'team', '', $params); ?>
							<?php overworld_core_get_cpt_single_module_template_part('templates/single/parts/comments', 'team'); ?>
						</div>
					</div>
					<?php if (!in_array($sidebar, array('no-sidebar', ''))) : ?>
		                <div <?php echo overworld_edge_get_sidebar_holder_class(); ?>>
		                    <?php get_sidebar(); ?>
		                </div>
		            <?php endif; ?>
				</div>
			</div>
		</div>
	<?php } ?>
<?php endwhile;	endif; ?>