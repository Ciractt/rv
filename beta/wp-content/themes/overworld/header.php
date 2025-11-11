<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php
	/**
	 * overworld_edge_action_header_meta hook
	 *
	 * @see overworld_edge_header_meta() - hooked with 10
	 * @see overworld_edge_user_scalable_meta - hooked with 10
	 * @see overworld_core_set_open_graph_meta - hooked with 10
	 */
	do_action( 'overworld_edge_action_header_meta' );
	
	wp_head(); ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
	<?php do_action( 'overworld_edge_action_after_opening_body_tag' ); ?>
    <div class="edgtf-wrapper">
        <div class="edgtf-wrapper-inner">
            <?php
            /**
             * overworld_edge_action_after_wrapper_inner hook
             *
             * @see overworld_edge_get_header() - hooked with 10
             * @see overworld_edge_get_mobile_header() - hooked with 20
             * @see overworld_edge_back_to_top_button() - hooked with 30
             * @see overworld_edge_get_header_minimal_full_screen_menu() - hooked with 40
             * @see overworld_edge_get_header_bottom_navigation() - hooked with 40
             */
            do_action( 'overworld_edge_action_after_wrapper_inner' ); ?>
	        
            <div class="edgtf-content" <?php overworld_edge_content_elem_style_attr(); ?>>
                <div class="edgtf-content-inner">