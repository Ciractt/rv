<?php

if ( ! function_exists( 'overworld_edge_register_search_post_type_widget' ) ) {
	/**
	 * Function that register search opener widget
	 */
	function overworld_edge_register_search_post_type_widget( $widgets ) {
		$widgets[] = 'OverworldEdgeClassSearchPostType';
		
		return $widgets;
	}
	
	add_filter( 'overworld_core_filter_register_widgets', 'overworld_edge_register_search_post_type_widget' );
}

if ( ! function_exists( 'overworld_edge_search_post_types' ) ) {
	function overworld_edge_search_post_types() {
		
		if ( empty( $_POST ) || ! isset( $_POST ) ) {
			overworld_edge_ajax_status( 'error', esc_html__( 'All fields are empty', 'overworld' ) );
		} else {
			check_ajax_referer( 'edgtf_search_post_types_nonce', 'search_post_types_nonce' );
			
			$args = array(
				'post_type'      => sanitize_text_field( $_POST['postType'] ),
				'post_status'    => 'publish',
				'order'          => 'DESC',
				'orderby'        => 'date',
				's'              => sanitize_text_field( $_POST['term'] ),
				'posts_per_page' => 5
			);
			
			$html  = '';
			$query = new WP_Query( $args );
			
			if ( $query->have_posts() ) {
				$html .= '<ul>';
					while ( $query->have_posts() ) {
						$query->the_post();
						$html .= '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
					}
				$html              .= '</ul>';
				$json_data['html'] = $html;
				overworld_edge_ajax_status( 'success', '', $json_data );
			} else {
				$html              .= '<ul>';
					$html              .= '<li>' . esc_html__( 'No posts found.', 'overworld' ) . '</li>';
				$html              .= '</ul>';
				$json_data['html'] = $html;
				overworld_edge_ajax_status( 'success', '', $json_data );
			}
		}
		
		wp_die();
	}
	
	add_action( 'wp_ajax_overworld_edge_search_post_types', 'overworld_edge_search_post_types' );
    add_action( 'wp_ajax_nopriv_overworld_edge_search_post_types', 'overworld_edge_search_post_types' );
}