<?php

namespace OverworldCore\CPT\Match;

use OverworldCore\Lib\PostTypeInterface;

/**
 * Class MatchRegister
 * @package OverworldCore\CPT\Match
 */
class MatchRegister implements PostTypeInterface {
	/**
	 * @var string
	 */
	private $base;
	private $taxBase;
	private $tagBase;

	public function __construct() {
		$this->base    = 'match';
		$this->taxBase = 'match-category';

		add_filter( 'archive_template', array( $this, 'registerArchiveTemplate' ) );
		add_filter( 'single_template', array( $this, 'registerSingleTemplate' ) );
	}

	/**
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/**
	 * Registers custom post type with WordPress
	 */
	public function register() {
		$this->registerPostType();
		$this->registerTax();
		$this->registerTagTax();
	}

	/**
	 * Registers player archive template if one does'nt exists in theme.
	 * Hooked to archive_template filter
	 *
	 * @param $archive string current template
	 *
	 * @return string string changed template
	 */
	public function registerArchiveTemplate( $archive ) {
		global $post;

		if ( ! empty( $post ) && $post->post_type == $this->base ) {
			if ( ! file_exists( get_template_directory() . '/archive-' . $this->base . '.php' ) ) {
				return OVERWORLD_CORE_CPT_PATH . '/match/templates/archive-' . $this->base . '.php';
			}
		}

		return $archive;
	}

	/**
	 * Registers match single template if one doesn't exists in theme.
	 * Hooked to single_template filter
	 *
	 * @param $single string current template
	 *
	 * @return string string changed template
	 */
	public function registerSingleTemplate( $single ) {
		global $post;

		if ( $post->post_type == $this->base ) {
			if ( ! file_exists( get_template_directory() . '/single-match.php' ) ) {
				return OVERWORLD_CORE_CPT_PATH . '/match/templates/single-' . $this->base . '.php';
			}
		}

		return $single;
	}

	/**
	 * Registers custom post type with WordPress
	 */
	private function registerPostType() {

		$menuPosition = 5;
		$menuIcon     = 'dashicons-admin-post';
		$slug         = $this->base;

		if ( overworld_core_theme_installed() ) {
			if ( overworld_edge_options()->getOptionValue( 'match_single_slug' ) ) {
				$slug = overworld_edge_options()->getOptionValue( 'match_single_slug' );
			}
		}

		register_post_type( $this->base,
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Overworld Match', 'overworld-core' ),
					'singular_name' => esc_html__( 'Match', 'overworld-core' ),
					'add_item'      => esc_html__( 'New Match', 'overworld-core' ),
					'add_new_item'  => esc_html__( 'Add New Match', 'overworld-core' ),
					'edit_item'     => esc_html__( 'Edit Match', 'overworld-core' )
				),
				'public'        => true,
				'has_archive'   => true,
				'rewrite'       => array( 'slug' => $slug ),
				'menu_position' => $menuPosition,
				'show_ui'       => true,
				'supports'      => array(
					'author',
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'page-attributes',
					'comments'
				),
				'menu_icon'     => $menuIcon
			)
		);
	}

	/**
	 * Registers custom taxonomy with WordPress
	 */
	private function registerTax() {
		$labels = array(
			'name'              => esc_html__( 'Match Categories', 'overworld-core' ),
			'singular_name'     => esc_html__( 'Match Category', 'overworld-core' ),
			'search_items'      => esc_html__( 'Search Match Categories', 'overworld-core' ),
			'all_items'         => esc_html__( 'All Match Categories', 'overworld-core' ),
			'parent_item'       => esc_html__( 'Parent Match Category', 'overworld-core' ),
			'parent_item_colon' => esc_html__( 'Parent Match Category:', 'overworld-core' ),
			'edit_item'         => esc_html__( 'Edit Match Category', 'overworld-core' ),
			'update_item'       => esc_html__( 'Update Match Category', 'overworld-core' ),
			'add_new_item'      => esc_html__( 'Add New Match Category', 'overworld-core' ),
			'new_item_name'     => esc_html__( 'New Match Category Name', 'overworld-core' ),
			'menu_name'         => esc_html__( 'Match Categories', 'overworld-core' ),
		);

		register_taxonomy( $this->taxBase, array( $this->base ), array(
			'hierarchical' => true,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'match-category' ),
		) );
	}

	/**
	 * Registers custom tag taxonomy with WordPress
	 */
	private function registerTagTax() {
		$labels = array(
			'name'              => esc_html__( 'Match Tags', 'overworld-core' ),
			'singular_name'     => esc_html__( 'Match Tag', 'overworld-core' ),
			'search_items'      => esc_html__( 'Search Match Tags', 'overworld-core' ),
			'all_items'         => esc_html__( 'All Match Tags', 'overworld-core' ),
			'parent_item'       => esc_html__( 'Parent Match Tag', 'overworld-core' ),
			'parent_item_colon' => esc_html__( 'Parent Match Tags:', 'overworld-core' ),
			'edit_item'         => esc_html__( 'Edit Match Tag', 'overworld-core' ),
			'update_item'       => esc_html__( 'Update Match Tag', 'overworld-core' ),
			'add_new_item'      => esc_html__( 'Add New Match Tag', 'overworld-core' ),
			'new_item_name'     => esc_html__( 'New Match Tag Name', 'overworld-core' ),
			'menu_name'         => esc_html__( 'Match Tags', 'overworld-core' ),
		);

		register_taxonomy( 'match-tag', array( $this->base ), array(
			'hierarchical' => false,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'match-tag' ),
		) );
	}
}