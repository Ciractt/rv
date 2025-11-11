<span class="edgtf-stars">
    <?php foreach ( $post_ratings as $rating ) { ?>
	    <span class="edgtf-stars-wrapper-inner">
            <span class="edgtf-stars-items">
                <?php
                $review_rating = overworld_core_post_average_rating( $rating );
                for ( $i = 1; $i <= $review_rating; $i ++ ) { ?>
	                <i class="fa fa-star" aria-hidden="true"></i>
                <?php } ?>
            </span>
		    <?php if ( isset( $rating['label'] ) && ! empty( $rating['label'] ) ) { ?>
			    <span class="edgtf-stars-label">
	                <?php echo esc_html( $rating['label'] ); ?>
	            </span>
		    <?php } ?>
        </span>
    <?php } ?>
</span>
<a itemprop="url" class="edgtf-post-info-comments" href="<?php comments_link(); ?>">
    <span class="edgtf-reviews-number">
        <?php echo esc_html( $rating_number ); ?>
    </span>
	<span class="edgtf-reviews-label">
        <?php echo esc_html( $rating_label ); ?>
    </span>
</a>