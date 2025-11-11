<?php

if ( ! function_exists('overworld_edge_contact_form_map') ) {
	/**
	 * Map Contact Form 7 shortcode
	 * Hooks on vc_after_init action
	 */
	function overworld_edge_contact_form_map() {
		vc_add_param('contact-form-7', array(
			'type' => 'dropdown',
			'heading' => esc_html__('Style', 'overworld'),
			'param_name' => 'html_class',
			'value' => array(
				esc_html__('Default', 'overworld') => 'default',
				esc_html__('Custom Style 1', 'overworld') => 'cf7_custom_style_1',
				esc_html__('Custom Style 2', 'overworld') => 'cf7_custom_style_2',
				esc_html__('Custom Style 3', 'overworld') => 'cf7_custom_style_3'
			),
			'description' => esc_html__('You can style each form element individually in Edge Options > Contact Form 7', 'overworld')
		));
	}
	
	add_action('vc_after_init', 'overworld_edge_contact_form_map');
}

// replace cf7 submit button with our button
remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit');
add_action('wpcf7_init', 'overworld_edge_cf7_button');

if (!function_exists('overworld_edge_cf7_button')) {
    function overworld_edge_cf7_button() {
        wpcf7_add_form_tag('submit', 'overworld_edge_cf7_button_handler');
    }
}

if (!function_exists('overworld_edge_cf7_button_handler')) {
    function overworld_edge_cf7_button_handler($tag) {
        $tag = new WPCF7_FormTag($tag);
        $class = wpcf7_form_controls_class($tag->type);

        $atts = array();
        $atts['class'] = $tag->get_class_option($class);
        $atts['class'] .= ' edgtf-btn edgtf-btn-medium edgtf-btn-solid';
        $atts['id'] = $tag->get_id_option();
        $atts['tabindex'] = $tag->get_option('tabindex', 'int', true);

        $value = isset($tag->values[0]) ? $tag->values[0] : '';
        if (empty($value)) {
            $value = esc_html__('Send', 'overworld');
        }

        $atts['type'] = 'submit';
        $atts = wpcf7_format_atts($atts);

        $html = sprintf('<button %1$s><span class="edgtf-btn-text">%2$s</span></button>', $atts, $value);

        return $html;
    }
}