<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
    <div class="edgtf-post-content">
        <div class="edgtf-post-heading">
            <?php overworld_edge_get_module_template_part('templates/parts/media', 'blog', $post_format, $part_params); ?>
        </div>
        <div class="edgtf-post-text">
            <div class="edgtf-post-text-inner">
                <div class="edgtf-post-info-top">
	                <?php overworld_edge_get_module_template_part('templates/parts/post-info/author', 'blog', '', $part_params); ?>
                    <?php overworld_edge_get_module_template_part('templates/parts/post-info/date', 'blog', '', $part_params); ?>
                    <?php overworld_edge_get_module_template_part('templates/parts/post-info/category', 'blog', '', $part_params); ?>
                </div>
                <div class="edgtf-post-text-main">
                    <?php overworld_edge_get_module_template_part('templates/parts/title', 'blog', '', $part_params); ?>
                    <?php overworld_edge_get_module_template_part('templates/parts/excerpt', 'blog', '', $part_params); ?>
                    <?php
                    if(overworld_edge_options()->getOptionValue('show_tags_area_blog') === 'yes') {
	                    overworld_edge_get_module_template_part('templates/parts/post-info/tags', 'blog', '', $part_params);
                    } ?>
                    <?php do_action('overworld_edge_action_single_link_pages'); ?>

                </div>
                <div class="edgtf-post-info-bottom clearfix">
                    <div class="edgtf-post-info-bottom-left">
	                    <?php
	                    if(overworld_edge_options()->getOptionValue('show_comments_area_blog') === 'yes') {
		                    overworld_edge_get_module_template_part('templates/parts/post-info/comments', 'blog', '', $part_params);
	                    } ?>
	                    <?php overworld_edge_get_module_template_part('templates/parts/post-info/read-more', 'blog', '', $part_params); ?>

                    </div>
                    <div class="edgtf-post-info-bottom-right">
                        <?php overworld_edge_get_module_template_part('templates/parts/post-info/share', 'blog', '', $part_params); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>