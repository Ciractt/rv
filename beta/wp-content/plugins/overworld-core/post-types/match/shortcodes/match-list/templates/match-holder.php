<div class="edgtf-match-list-holder edgtf-grid-list edgtf-disable-bottom-space">
	<div class="edgtf-pl-inner edgtf-outer-space">
		<?php
			if($query_results->have_posts()):
				while ( $query_results->have_posts() ) : $query_results->the_post();
					$params['match_id'] = get_the_ID();
					echo overworld_edge_execute_shortcode('edgtf_single_match', $params);
				endwhile;
			else:
				esc_html_e( 'Sorry, no posts matched your criteria.', 'overworld-core' );
			endif;
		
			wp_reset_postdata();
		?>
	</div>
</div>