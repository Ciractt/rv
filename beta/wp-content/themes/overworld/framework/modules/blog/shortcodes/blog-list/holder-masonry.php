<div class="edgtf-blog-list-holder edgtf-grid-list edgtf-grid-masonry-list <?php echo esc_attr( $holder_classes ); ?>" <?php echo wp_kses( $holder_data, array( 'data' ) ); ?>>
	<div class="edgtf-bl-wrapper edgtf-outer-space">
		<ul class="edgtf-blog-list edgtf-masonry-list-wrapper">
			<li class="edgtf-masonry-grid-sizer"></li>
			<li class="edgtf-masonry-grid-gutter"></li>
			<?php
			if ( $query_result->have_posts() ):
				while ( $query_result->have_posts() ) : $query_result->the_post();
					overworld_edge_get_module_template_part( 'shortcodes/blog-list/layout-collections/post', 'blog', $type, $params );
				endwhile;
			else:
				overworld_edge_get_module_template_part( 'templates/parts/no-posts', 'blog', '', $params );
			endif;
			
			wp_reset_postdata();
			?>
		</ul>
	</div>
	<?php overworld_edge_get_module_template_part( 'templates/parts/pagination/' . $params['pagination_type'], 'blog', '', $params ); ?>
</div>