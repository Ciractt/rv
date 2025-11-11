<?php

if ( ! function_exists( 'overworld_edge_sidearea_options_map' ) ) {
	function overworld_edge_sidearea_options_map() {

        overworld_edge_add_admin_page(
            array(
                'slug'  => '_side_area_page',
                'title' => esc_html__('Side Area', 'overworld'),
                'icon'  => 'fa fa-indent'
            )
        );

        $side_area_panel = overworld_edge_add_admin_panel(
            array(
                'title' => esc_html__('Side Area', 'overworld'),
                'name'  => 'side_area',
                'page'  => '_side_area_page'
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_panel,
                'type'          => 'select',
                'name'          => 'side_area_type',
                'default_value' => 'side-menu-slide-from-right',
                'label'         => esc_html__('Side Area Type', 'overworld'),
                'description'   => esc_html__('Choose a type of Side Area', 'overworld'),
                'options'       => array(
                    'side-menu-slide-from-right'       => esc_html__('Slide from Right Over Content', 'overworld'),
                    'side-menu-slide-with-content'     => esc_html__('Slide from Right With Content', 'overworld'),
                    'side-area-uncovered-from-content' => esc_html__('Side Area Uncovered from Content', 'overworld'),
                ),
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_panel,
                'type'          => 'text',
                'name'          => 'side_area_width',
                'default_value' => '',
                'label'         => esc_html__('Side Area Width', 'overworld'),
                'description'   => esc_html__('Enter a width for Side Area (px or %). Default width: 448px.', 'overworld'),
                'args'          => array(
                    'col_width' => 3,
                )
            )
        );

        $side_area_width_container = overworld_edge_add_admin_container(
            array(
                'parent'     => $side_area_panel,
                'name'       => 'side_area_width_container',
                'dependency' => array(
                    'show' => array(
                        'side_area_type' => 'side-menu-slide-from-right',
                    )
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_width_container,
                'type'          => 'color',
                'name'          => 'side_area_content_overlay_color',
                'default_value' => '',
                'label'         => esc_html__('Content Overlay Background Color', 'overworld'),
                'description'   => esc_html__('Choose a background color for a content overlay', 'overworld'),
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_width_container,
                'type'          => 'text',
                'name'          => 'side_area_content_overlay_opacity',
                'default_value' => '',
                'label'         => esc_html__('Content Overlay Background Transparency', 'overworld'),
                'description'   => esc_html__('Choose a transparency for the content overlay background color (0 = fully transparent, 1 = opaque)', 'overworld'),
                'args'          => array(
                    'col_width' => 3
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_panel,
                'type'          => 'select',
                'name'          => 'side_area_icon_source',
                'default_value' => 'icon_pack',
                'label'         => esc_html__('Select Side Area Icon Source', 'overworld'),
                'description'   => esc_html__('Choose whether you would like to use icons from an icon pack or SVG icons', 'overworld'),
                'options'       => overworld_edge_get_icon_sources_array()
            )
        );

        $side_area_icon_pack_container = overworld_edge_add_admin_container(
            array(
                'parent'     => $side_area_panel,
                'name'       => 'side_area_icon_pack_container',
                'dependency' => array(
                    'show' => array(
                        'side_area_icon_source' => 'icon_pack'
                    )
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_icon_pack_container,
                'type'          => 'select',
                'name'          => 'side_area_icon_pack',
                'default_value' => 'font_elegant',
                'label'         => esc_html__('Side Area Icon Pack', 'overworld'),
                'description'   => esc_html__('Choose icon pack for Side Area icon', 'overworld'),
                'options'       => overworld_edge_icon_collections()->getIconCollectionsExclude(array('linea_icons', 'dripicons', 'simple_line_icons'))
            )
        );

        $side_area_svg_icons_container = overworld_edge_add_admin_container(
            array(
                'parent'     => $side_area_panel,
                'name'       => 'side_area_svg_icons_container',
                'dependency' => array(
                    'show' => array(
                        'side_area_icon_source' => 'svg_path'
                    )
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'      => $side_area_svg_icons_container,
                'type'        => 'textarea',
                'name'        => 'side_area_icon_svg_path',
                'label'       => esc_html__('Side Area Icon SVG Path', 'overworld'),
                'description' => esc_html__('Enter your Side Area icon SVG path here. Please remove version and id attributes from your SVG path because of HTML validation', 'overworld'),
            )
        );

        $side_area_icon_style_group = overworld_edge_add_admin_group(
            array(
                'parent'      => $side_area_panel,
                'name'        => 'side_area_icon_style_group',
                'title'       => esc_html__('Side Area Icon Style', 'overworld'),
                'description' => esc_html__('Define styles for Side Area icon', 'overworld')
            )
        );

        $side_area_icon_style_row1 = overworld_edge_add_admin_row(
            array(
                'parent' => $side_area_icon_style_group,
                'name'   => 'side_area_icon_style_row1'
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent' => $side_area_icon_style_row1,
                'type'   => 'colorsimple',
                'name'   => 'side_area_icon_color',
                'label'  => esc_html__('Color', 'overworld')
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent' => $side_area_icon_style_row1,
                'type'   => 'colorsimple',
                'name'   => 'side_area_icon_hover_color',
                'label'  => esc_html__('Hover Color', 'overworld')
            )
        );

        $side_area_icon_style_row2 = overworld_edge_add_admin_row(
            array(
                'parent' => $side_area_icon_style_group,
                'name'   => 'side_area_icon_style_row2',
                'next'   => true
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent' => $side_area_icon_style_row2,
                'type'   => 'colorsimple',
                'name'   => 'side_area_close_icon_color',
                'label'  => esc_html__('Close Icon Color', 'overworld')
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'      => $side_area_panel,
                'type'        => 'color',
                'name'        => 'side_area_background_color',
                'label'       => esc_html__('Background Color', 'overworld'),
                'description' => esc_html__('Choose a background color for Side Area', 'overworld')
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'      => $side_area_panel,
                'type'        => 'text',
                'name'        => 'side_area_padding',
                'label'       => esc_html__('Padding', 'overworld'),
                'description' => esc_html__('Define padding for Side Area in format top right bottom left', 'overworld'),
                'args'        => array(
                    'col_width' => 3
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_panel,
                'type'          => 'selectblank',
                'name'          => 'side_area_aligment',
                'default_value' => '',
                'label'         => esc_html__('Text Alignment', 'overworld'),
                'description'   => esc_html__('Choose text alignment for side area', 'overworld'),
                'options'       => array(
                    ''       => esc_html__('Default', 'overworld'),
                    'left'   => esc_html__('Left', 'overworld'),
                    'center' => esc_html__('Center', 'overworld'),
                    'right'  => esc_html__('Right', 'overworld')
                )
            )
        );

        overworld_edge_add_admin_field(
            array(
                'parent'        => $side_area_panel,
                'type'          => 'selectblank',
                'name'          => 'side_area_justify_content',
                'default_value' => '',
                'label'         => esc_html__('Vertical Content Arrangement', 'overworld'),
                'description'   => esc_html__('Choose widget vertical arrangement type for side area', 'overworld'),
                'options'       => array(
	                ''              => esc_html__( 'Default', 'overworld' ),
	                'space-around'  => esc_html__( 'Space Around', 'overworld' ),
	                'space-between' => esc_html__( 'Space Between', 'overworld' ),
	                'space-evenly'  => esc_html__( 'Space Evenly', 'overworld' )
                )
            )
        );
    }

    add_action('overworld_edge_action_options_map', 'overworld_edge_sidearea_options_map', overworld_edge_set_options_map_position( 'sidearea' ) );
}