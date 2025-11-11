<?php

if (!function_exists('overworld_edge_team_options_map')) {

    function overworld_edge_team_options_map() {

        overworld_edge_add_admin_page(array(
            'slug'  => '_team',
            'title' => esc_html__('Team', 'overworld-core'),
            'icon'  => 'fa fa-gamepad'
        ));

        $panel_team = overworld_edge_add_admin_panel(array(
            'title' => esc_html__('Team Single', 'overworld-core'),
            'name'  => 'panel_team_single',
            'page'  => '_team'
        ));

        overworld_edge_add_admin_field(array(
            'name'          => 'team_single_comments',
            'type'          => 'yesno',
            'label'         => esc_html__('Show Comments', 'overworld-core'),
            'description'   => esc_html__('Enabling this option will show comments on your page.', 'overworld-core'),
            'parent'        => $panel_team,
            'default_value' => 'yes'
        ));

	    overworld_edge_add_admin_field(
            array(
                'name'            => 'team_bg_image',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'description'     => esc_html__('This is default background image that will appear on all team single pages.', 'overworld-core'),
                'parent'          => $panel_team,
            )
        );
    }

    add_action('overworld_edge_action_options_map', 'overworld_edge_team_options_map', 15);

}