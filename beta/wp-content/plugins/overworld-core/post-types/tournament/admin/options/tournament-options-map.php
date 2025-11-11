<?php

if (!function_exists('overworld_edge_tournament_options_map')) {

    function overworld_edge_tournament_options_map() {

        overworld_edge_add_admin_page(array(
            'slug'  => '_tournament',
            'title' => esc_html__('Tournament', 'overworld-core'),
            'icon'  => 'fa fa-gamepad'
        ));

        $panel_tournament = overworld_edge_add_admin_panel(array(
            'title' => esc_html__('Tournament Single', 'overworld-core'),
            'name'  => 'panel_tournament_single',
            'page'  => '_tournament'
        ));

        overworld_edge_add_admin_field(array(
            'name'          => 'tournament_single_comments',
            'type'          => 'yesno',
            'label'         => esc_html__('Show Comments', 'overworld-core'),
            'description'   => esc_html__('Enabling this option will show comments on your page.', 'overworld-core'),
            'parent'        => $panel_tournament,
            'default_value' => 'yes'
        ));

	    overworld_edge_add_admin_field(
            array(
                'name'            => 'tournament_bg_image',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'description'     => esc_html__('This is default background image that will appear on all tournament single pages.', 'overworld-core'),
                'parent'          => $panel_tournament,
            )
        );
    }

    add_action('overworld_edge_action_options_map', 'overworld_edge_tournament_options_map', 15);

}