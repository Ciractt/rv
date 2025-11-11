<?php
$show_related = overworld_edge_options()->getOptionValue('blog_single_related_posts') == 'yes';
$related_post_number = overworld_edge_sidebar_layout() === 'no-sidebar' ? 4 : 3;
$related_posts_options = array(
    'posts_per_page' => $related_post_number
);
$related_posts = overworld_edge_get_blog_related_post_type(get_the_ID(), $related_posts_options);
$related_posts_image_size = isset($related_posts_image_size) ? $related_posts_image_size : 'full';
?>
<?php if($show_related) { ?>
    <div class="edgtf-related-posts-holder clearfix">
        <?php if ( $related_posts && $related_posts->have_posts() ) : ?>
            <h3 class="edgtf-related-posts-title"><?php esc_html_e('Related Posts', 'overworld' ); ?></h3>
            <div class="edgtf-related-posts-inner clearfix">
                <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
                    <div class="edgtf-related-post">
                        <div class="edgtf-related-post-inner">
		                    <?php if (has_post_thumbnail()) { ?>
                            <div class="edgtf-related-post-image">
                                <a itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                     <?php the_post_thumbnail($related_posts_image_size); ?>
                                </a>
                            </div>
		                    <?php }	?>
                            <h5 itemprop="name" class="entry-title edgtf-post-title"><a itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
                            <div class="edgtf-post-info">
                                <?php overworld_edge_get_module_template_part('templates/parts/post-info/date', 'blog', '', $params); ?>
                                <?php overworld_edge_get_module_template_part('templates/parts/post-info/author', 'blog', '', $params); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
<?php } ?>