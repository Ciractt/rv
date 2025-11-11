<?php
get_header();
overworld_edge_get_title();
do_action('overworld_edge_action_before_main_content'); ?>
<div class="edgtf-container edgtf-default-page-template">
	<?php do_action('overworld_edge_action_after_container_open'); ?>
	<div class="edgtf-container-inner clearfix">
		<?php
			$overworld_taxonomy_id = get_queried_object_id();
			$overworld_taxonomy = !empty($overworld_taxonomy_id) ? get_term_by( 'id', $overworld_taxonomy_id, 'player-category' ) : '';
			$overworld_taxonomy_slug = !empty($overworld_taxonomy) ? $overworld_taxonomy->slug : '';
		
			overworld_core_get_player_category_list($overworld_taxonomy_slug);
		?>
	</div>
	<?php do_action('overworld_edge_action_before_container_close'); ?>
</div>
<?php get_footer(); ?>
