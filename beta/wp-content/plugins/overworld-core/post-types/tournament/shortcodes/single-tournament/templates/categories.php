<?php
$categories   = wp_get_post_terms($tournament_id, 'tournament-category');
$category_names = array();

if(is_array($categories) && count($categories)) :
	foreach($categories as $category) {
		$category_names[] = $category->name;
	}

	?>
    <div class="edgtf-tournament-categories"><span class="edgtf-tournament-category"><?php echo wp_kses(implode('</span><span class="edgtf-tournament-category">', $category_names), array('span' => array('class' => true))); ?></span></div>
<?php endif; ?>