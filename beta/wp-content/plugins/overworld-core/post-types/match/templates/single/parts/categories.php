<?php
$categories   = wp_get_post_terms(get_the_ID(), 'match-category');
$category_names = array();

if(is_array($categories) && count($categories)) :
	foreach($categories as $category) {
		$category_names[] = $category->name;
	}

	?>
    <div class="edgtf-match-categories"><span class="edgtf-match-category"><?php echo wp_kses(implode('</span><span class="edgtf-match-category">', $category_names), array('span' => array('class' => true))); ?></span></div>
<?php endif; ?>