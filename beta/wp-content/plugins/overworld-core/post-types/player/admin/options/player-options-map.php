<?php

if (!function_exists('overworld_edge_player_options_map')) {

    function overworld_edge_player_options_map() {

        overworld_edge_add_admin_page(array(
            'slug'  => '_player',
            'title' => esc_html__('Player', 'overworld-core'),
            'icon'  => 'fa fa-gamepad'
        ));

        $panel_player = overworld_edge_add_admin_panel(array(
            'title' => esc_html__('Player Single', 'overworld-core'),
            'name'  => 'panel_player_single',
            'page'  => '_player'
        ));

        overworld_edge_add_admin_field(array(
            'name'          => 'player_single_comments',
            'type'          => 'yesno',
            'label'         => esc_html__('Show Comments', 'overworld-core'),
            'description'   => esc_html__('Enabling this option will show comments on your page.', 'overworld-core'),
            'parent'        => $panel_player,
            'default_value' => 'yes'
        ));

	    overworld_edge_add_admin_field(
            array(
                'name'            => 'player_bg_image',
                'type'            => 'image',
                'label'           => esc_html__('Custom Background Image', 'overworld-core'),
                'description'     => esc_html__('This is default background image that will appear on all player single pages.', 'overworld-core'),
                'parent'          => $panel_player,
            )
        );
    }

    add_action('overworld_edge_action_options_map', 'overworld_edge_player_options_map', 15);

}