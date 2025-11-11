<?php

namespace OverworldCore\CPT\Team;

use OverworldCore\Lib\PostTypeInterface;

/**
 * Class TeamRegister
 * @package OverworldCore\CPT\Team
 */
class TeamRegister implements PostTypeInterface {
	/**
	 * @var string
	 */
	private $base;
	private $taxBase;
	private $tagBase;

	public function __construct() {
		$this->base    = 'team';
		$this->taxBase = 'team-category';

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
				return OVERWORLD_CORE_CPT_PATH . '/team/templates/archive-' . $this->base . '.php';
			}
		}

		return $archive;
	}

	/**
	 * Registers team single template if one doesn't exists in theme.
	 * Hooked to single_template filter
	 *
	 * @param $single string current template
	 *
	 * @return string string changed template
	 */
	public function registerSingleTemplate( $single ) {
		global $post;

		if ( $post->post_type == $this->base ) {
			if ( ! file_exists( get_template_directory() . '/single-team.php' ) ) {
				return OVERWORLD_CORE_CPT_PATH . '/team/templates/single-' . $this->base . '.php';
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
			if ( overworld_edge_options()->getOptionValue( 'team_single_slug' ) ) {
				$slug = overworld_edge_options()->getOptionValue( 'team_single_slug' );
			}
		}

		register_post_type( $this->base,
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Overworld Team', 'overworld-core' ),
					'singular_name' => esc_html__( 'Team', 'overworld-core' ),
					'add_item'      => esc_html__( 'New Team', 'overworld-core' ),
					'add_new_item'  => esc_html__( 'Add New Team', 'overworld-core' ),
					'edit_item'     => esc_html__( 'Edit Team', 'overworld-core' )
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
			'name'              => esc_html__( 'Team Categories', 'overworld-core' ),
			'singular_name'     => esc_html__( 'Team Category', 'overworld-core' ),
			'search_items'      => esc_html__( 'Search Team Categories', 'overworld-core' ),
			'all_items'         => esc_html__( 'All Team Categories', 'overworld-core' ),
			'parent_item'       => esc_html__( 'Parent Team Category', 'overworld-core' ),
			'parent_item_colon' => esc_html__( 'Parent Team Category:', 'overworld-core' ),
			'edit_item'         => esc_html__( 'Edit Team Category', 'overworld-core' ),
			'update_item'       => esc_html__( 'Update Team Category', 'overworld-core' ),
			'add_new_item'      => esc_html__( 'Add New Team Category', 'overworld-core' ),
			'new_item_name'     => esc_html__( 'New Team Category Name', 'overworld-core' ),
			'menu_name'         => esc_html__( 'Team Categories', 'overworld-core' ),
		);

		register_taxonomy( $this->taxBase, array( $this->base ), array(
			'hierarchical' => true,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'team-category' ),
		) );
	}

	/**
	 * Registers custom tag taxonomy with WordPress
	 */
	private function registerTagTax() {
		$labels = array(
			'name'              => esc_html__( 'Team Tags', 'overworld-core' ),
			'singular_name'     => esc_html__( 'Team Tag', 'overworld-core' ),
			'search_items'      => esc_html__( 'Search Team Tags', 'overworld-core' ),
			'all_items'         => esc_html__( 'All Team Tags', 'overworld-core' ),
			'parent_item'       => esc_html__( 'Parent Team Tag', 'overworld-core' ),
			'parent_item_colon' => esc_html__( 'Parent Team Tags:', 'overworld-core' ),
			'edit_item'         => esc_html__( 'Edit Team Tag', 'overworld-core' ),
			'update_item'       => esc_html__( 'Update Team Tag', 'overworld-core' ),
			'add_new_item'      => esc_html__( 'Add New Team Tag', 'overworld-core' ),
			'new_item_name'     => esc_html__( 'New Team Tag Name', 'overworld-core' ),
			'menu_name'         => esc_html__( 'Team Tags', 'overworld-core' ),
		);

		register_taxonomy( 'team-tag', array( $this->base ), array(
			'hierarchical' => false,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'team-tag' ),
		) );
	}
}