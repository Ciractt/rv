<?php

namespace OverworldCore\CPT\Player;

use OverworldCore\Lib\PostTypeInterface;

class PlayerRegister implements PostTypeInterface {
	private $base;
	
	public function __construct() {
		$this->base    = 'player';
		$this->taxBase = 'player-category';
		
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
				return OVERWORLD_CORE_CPT_PATH . '/player/templates/archive-' . $this->base . '.php';
			}
		}
		
		return $archive;
	}
	
	/**
	 * Registers player single template if one does'nt exists in theme.
	 * Hooked to single_template filter
	 *
	 * @param $single string current template
	 *
	 * @return string string changed template
	 */
	public function registerSingleTemplate( $single ) {
		global $post;
		
		if ( ! empty( $post ) && $post->post_type == $this->base ) {
			if ( ! file_exists( get_template_directory() . '/single-' . $this->base . '.php' ) ) {
				return OVERWORLD_CORE_CPT_PATH . '/player/templates/single-' . $this->base . '.php';
			}
		}
		
		return $single;
	}
	
	/**
	 * Registers custom post type with WordPress
	 */
	private function registerPostType() {
		$menuPosition = 5;
		$menuIcon     = 'dashicons-admin-users';
		$slug         = $this->base;
		
		register_post_type( $this->base,
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Overworld Player', 'overworld-core' ),
					'singular_name' => esc_html__( 'Player', 'overworld-core' ),
					'add_item'      => esc_html__( 'New Player', 'overworld-core' ),
					'add_new_item'  => esc_html__( 'Add New Player', 'overworld-core' ),
					'edit_item'     => esc_html__( 'Edit Player', 'overworld-core' )
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
			'name'              => esc_html__( 'Player Categories', 'overworld-core' ),
			'singular_name'     => esc_html__( 'Player Category', 'overworld-core' ),
			'search_items'      => esc_html__( 'Search Player Categories', 'overworld-core' ),
			'all_items'         => esc_html__( 'All Player Categories', 'overworld-core' ),
			'parent_item'       => esc_html__( 'Parent Player Category', 'overworld-core' ),
			'parent_item_colon' => esc_html__( 'Parent Player Category:', 'overworld-core' ),
			'edit_item'         => esc_html__( 'Edit Player Category', 'overworld-core' ),
			'update_item'       => esc_html__( 'Update Player Category', 'overworld-core' ),
			'add_new_item'      => esc_html__( 'Add New Player Category', 'overworld-core' ),
			'new_item_name'     => esc_html__( 'New Player Category Name', 'overworld-core' ),
			'menu_name'         => esc_html__( 'Player Categories', 'overworld-core' )
		);
		
		register_taxonomy( $this->taxBase, array( $this->base ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => $this->taxBase )
		) );
	}
}