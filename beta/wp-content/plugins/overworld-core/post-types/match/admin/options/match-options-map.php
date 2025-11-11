<?php

if (!function_exists('overworld_edge_match_options_map')) {

    function overworld_edge_match_options_map() {

        overworld_edge_add_admin_page(array(
            'slug'  => '_match',
            'title' => esc_html__('Match', 'overworld-core'),
            'icon'  => 'fa fa-gamepad'
        ));

        $panel_match = overworld_edge_add_admin_panel(array(
            'title' => esc_html__('Match Single', 'overworld-core'),
            'name'  => 'panel_match_single',
            'page'  => '_match'
        ));

        overworld_edge_add_admin_field(array(
            'name'          => 'match_single_comments',
            'type'          => 'yesno',
            'label'         => esc_html__('Show Comments', 'overworld-core'),
            'description'   => esc_html__('Enabling this option will show comments on your page.', 'overworld-core'),
            'parent'        => $panel_match,
            'default_value' => 'yes'
        ));

	    overworld_edge_add_admin_field(
            array(
                'name'            => 'match_bg_image',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'description'     => esc_html__('This is default background image that will appear on all match single pages.', 'overworld-core'),
                'parent'          => $panel_match,
            )
        );
    }

    add_action('overworld_edge_action_options_map', 'overworld_edge_match_options_map', 15);

}