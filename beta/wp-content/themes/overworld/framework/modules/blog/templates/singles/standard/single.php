<?php

overworld_edge_get_single_post_format_html( $blog_single_type );

do_action( 'overworld_edge_action_after_article_content' );

overworld_edge_get_module_template_part( 'templates/parts/single/single-navigation', 'blog' );

overworld_edge_get_module_template_part( 'templates/parts/single/related-posts', 'blog', '', $single_info_params );