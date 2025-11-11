<?php if ( is_array( $post_ratings ) && count( $post_ratings ) ) {
	$average_rating_total = intval( round( overworld_core_get_total_average_rating( $post_ratings ) ) );
	?>
	<div class="edgtf-reviews-list-info edgtf-reviews-average-count">
		<span class="edgtf-reviews-number">
            <?php echo esc_html( $rating_number ); ?>
        </span>
		<span class="edgtf-reviews-label">
            <?php echo esc_html( $rating_label ); ?>
        </span>
		<span class="edgtf-stars">
		    <span class="edgtf-stars-wrapper-inner">
	            <span class="edgtf-stars-items">
	                <?php for ( $i = 1; $i <= 5; $i ++ ) {
	                	if ( $average_rating_total >= $i ) { ?>
			                <i class="fas fa-star" aria-hidden="true"></i>
		                <?php } else { ?>
			                <i class="far fa-star" aria-hidden="true"></i>
		                <?php }
	                } ?>
	            </span>
	        </span>
		</span>
	</div>
<?php } ?>