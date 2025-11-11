<?php if( overworld_edge_is_plugin_installed( 'core' ) ) { ?>
    <div class="edgtf-blog-like">
        <?php if( function_exists('overworld_core_get_like') ) overworld_core_get_like(); ?>
    </div>
<?php } ?>