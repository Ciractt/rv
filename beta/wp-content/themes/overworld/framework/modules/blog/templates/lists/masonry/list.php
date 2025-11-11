<div class="<?php echo esc_attr( $blog_classes ) ?>" <?php echo esc_attr( $blog_data_params ) ?>>
	<div class="edgtf-blog-holder-inner edgtf-outer-space edgtf-masonry-list-wrapper">
		<div class="edgtf-masonry-grid-sizer"></div>
		<div class="edgtf-masonry-grid-gutter"></div>
		<?php
		if ( $blog_query->have_posts() ) : while ( $blog_query->have_posts() ) : $blog_query->the_post();
			overworld_edge_get_post_format_html( $blog_type );
		endwhile;
		else:
			overworld_edge_get_module_template_part( 'templates/parts/no-posts', 'blog' );
		endif;
		
		wp_reset_postdata();
		?>
	</div>
	<?php overworld_edge_get_module_template_part( 'templates/parts/pagination/pagination', 'blog', '', $params ); ?>
</div>