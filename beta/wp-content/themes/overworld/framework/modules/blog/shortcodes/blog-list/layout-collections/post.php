<li class="edgtf-bl-item edgtf-item-space">
	<div class="edgtf-bli-inner">
		<?php if ( $post_info_image == 'yes' ) {
			overworld_edge_get_module_template_part( 'templates/parts/media', 'blog', '', $params );
		} ?>
        <div class="edgtf-bli-content">
	
                <?php overworld_edge_get_module_template_part( 'templates/parts/title', 'blog', '', $params ); ?>
    
                <?php if ($post_info_section == 'yes') { ?>
                    <div class="edgtf-bli-info">
                        <?php
                        if ( $post_info_date == 'yes' ) {
                            overworld_edge_get_module_template_part( 'templates/parts/post-info/date', 'blog', '', $params );
                        }
                        if ( $post_info_category == 'yes' ) {
                            overworld_edge_get_module_template_part( 'templates/parts/post-info/category', 'blog', '', $params );
                        }
                        if ( $post_info_author == 'yes' ) {
                            overworld_edge_get_module_template_part( 'templates/parts/post-info/author', 'blog', '', $params );
                        }
                        if ( $post_info_comments == 'yes' ) {
                            overworld_edge_get_module_template_part( 'templates/parts/post-info/comments', 'blog', '', $params );
                        }
                        if ( $post_info_like == 'yes' ) {
                            overworld_edge_get_module_template_part( 'templates/parts/post-info/like', 'blog', '', $params );
                        }
                        if ( $post_info_share == 'yes' ) {
                            overworld_edge_get_module_template_part( 'templates/parts/post-info/share', 'blog', '', $params );
                        }
                        ?>
                    </div>
                <?php } ?>
    
                <?php  if ( $excerpt_length !== '0' ) { ?>
                    <div class="edgtf-bli-excerpt">
                        <?php overworld_edge_get_module_template_part( 'templates/parts/excerpt', 'blog', '', $params ); ?>
                    </div>
                <?php } ?>
        </div>
	</div>
</li>