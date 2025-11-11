<?php

if ( ! function_exists( 'overworld_core_action_like' ) ) {
	/**
	 * Returns OverworldEdgeClassLike instance
	 *
	 * @return OverworldEdgeClassLike
	 */
	function overworld_core_action_like() {
		return OverworldEdgeClassLike::get_instance();
	}
}

function overworld_core_get_like() {
	
	echo wp_kses( overworld_core_action_like()->add_like(), array(
		'span'  => array(
			'class'       => true,
			'aria-hidden' => true,
			'style'       => true,
			'id'          => true
		),
		'i'     => array(
			'class' => true,
			'style' => true,
			'id'    => true
		),
		'a'     => array(
			'href'         => true,
			'class'        => true,
			'id'           => true,
			'title'        => true,
			'style'        => true,
			'data-post-id' => true
		),
		'input' => array(
			'type'  => true,
			'name'  => true,
			'id'    => true,
			'value' => true
		)
	) );
}