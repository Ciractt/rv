<?php
$categories   = wp_get_post_terms($team_id, 'team-category');
$category_names = array();

if(is_array($categories) && count($categories)) :
	foreach($categories as $category) {
		$category_names[] = $category->name;
	}

	?>
    <div class="edgtf-team-categories"><span class="edgtf-team-category"><?php echo wp_kses(implode('</span><span class="edgtf-team-category">', $category_names), array('span' => array('class' => true))); ?></span></div>
<?php endif; ?>