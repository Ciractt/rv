<?php

if ( ! function_exists( 'overworld_edge_get_subscribe_popup' ) ) {
	/**
	 * Loads search HTML based on search type option.
	 */
	function overworld_edge_get_subscribe_popup() {

		if ( overworld_edge_options()->getOptionValue( 'enable_subscribe_popup' ) === 'yes' && ( overworld_edge_options()->getOptionValue( 'subscribe_popup_contact_form' ) !== '' || overworld_edge_options()->getOptionValue( 'subscribe_popup_title' ) !== '' ) ) {
			overworld_edge_load_subscribe_popup_template();
		}
	}

	//Get subscribe popup HTML
	add_action( 'overworld_edge_action_before_page_header', 'overworld_edge_get_subscribe_popup' );
}

if ( ! function_exists( 'overworld_edge_load_subscribe_popup_template' ) ) {
	/**
	 * Loads HTML template with parameters
	 */
	function overworld_edge_load_subscribe_popup_template() {
		$parameters                       = array();
		$parameters['title']              = overworld_edge_options()->getOptionValue( 'subscribe_popup_title' );
		$parameters['subtitle']           = overworld_edge_options()->getOptionValue( 'subscribe_popup_subtitle' );
		$background_image_meta            = overworld_edge_options()->getOptionValue( 'subscribe_popup_background_image' );
		$parameters['background_styles']  = ! empty( $background_image_meta ) ? 'background-image: url(' . esc_url( $background_image_meta ) . ')' : '';
		$parameters['contact_form']       = overworld_edge_options()->getOptionValue( 'subscribe_popup_contact_form' );
		$parameters['contact_form_style'] = overworld_edge_options()->getOptionValue( 'subscribe_popup_contact_form_style' );
		$parameters['enable_prevent']     = overworld_edge_options()->getOptionValue( 'enable_subscribe_popup_prevent' );
		$parameters['prevent_behavior']   = overworld_edge_options()->getOptionValue( 'subscribe_popup_prevent_behavior' );

		$holder_classes   = array();
		$holder_classes[] = $parameters['enable_prevent'] === 'yes' ? 'edgtf-prevent-enable' : 'edgtf-prevent-disable';
		$holder_classes[] = ! empty( $parameters['prevent_behavior'] ) ? 'edgtf-sp-prevent-' . $parameters['prevent_behavior'] : 'edgtf-sp-prevent-session';
		$holder_classes[] = ! empty( $background_image_meta ) ? 'edgtf-sp-has-image' : '';

		$parameters['holder_classes'] = implode( ' ', $holder_classes );

		overworld_edge_get_module_template_part( 'templates/subscribe-popup', 'subscribe-popup', '', $parameters );
	}
}