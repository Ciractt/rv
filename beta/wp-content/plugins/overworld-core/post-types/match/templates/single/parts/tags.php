<?php
$tags      = wp_get_post_terms( get_the_ID(), 'match-tag' );
$tag_names = array();

if(is_array($tags) && count($tags)) :
	foreach($tags as $tag) {
		$tag_names[] = $tag->name;
	} ?>
    <div class="edgtf-match-tags-holder">
	    <h5><?php echo esc_html( 'Tags:', 'overworld' ) ?></h5>
	    <div class="edgtf-match-tags">
		    <span class="edgtf-match-tag"><?php echo wp_kses( implode( '<span class="edgtf-match-tag-separator"> / </span>', $tag_names ), array( 'span' => array( 'class' => true ) ) ); ?></span>
	    </div>
    </div>
<?php endif; ?>
