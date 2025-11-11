<div class="edgtf-reviews-list-info edgtf-reviews-per-mark">
	<?php foreach ( $post_ratings as $rating ) { ?>
		<?php
		$average_rating     = overworld_core_post_average_rating( $rating );
		$rating_count       = $rating['count'];
		$rating_count_label = $rating_count === 1 ? esc_html__( 'Rating', 'overworld-core' ) : esc_html__( 'Ratings', 'overworld-core' );
		$rating_marks       = $rating['marks'];
		?>
		<div class="edgtf-grid-row">
			<div class="edgtf-grid-col-4">
				<div class="edgtf-reviews-number-wrapper">
					<span class="edgtf-reviews-number"><?php echo esc_html( $average_rating ); ?></span>
					<span class="edgtf-stars-wrapper">
                        <span class="edgtf-stars">
                            <?php
                            for ( $i = 1; $i <= $average_rating; $i ++ ) { ?>
	                            <i class="fa fa-star" aria-hidden="true"></i>
                            <?php } ?>
                        </span>
                        <span class="edgtf-reviews-count">
                            <?php echo esc_html__( 'Rated', 'overworld-core' ) . ' ' . $average_rating . ' ' . esc_html__( 'out of', 'overworld-core' ) . ' ' . $rating_count . ' ' . $rating_count_label; ?>
                        </span>
                    </span>
				</div>
			</div>
			<div class="edgtf-grid-col-8">
				<div class="edgtf-rating-percentage-wrapper">
					<?php
					foreach ( $rating_marks as $item => $value ) {
						$percentage = $rating_count == 0 ? 0 : round( ( $value / $rating_count ) * 100 );
						echo do_shortcode( '[edgtf_progress_bar percent="' . esc_attr( $percentage ) . '" title="' . esc_attr( $item ) . esc_html__( ' stars', 'overworld-core' ) . '"]' );
					}
					?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
