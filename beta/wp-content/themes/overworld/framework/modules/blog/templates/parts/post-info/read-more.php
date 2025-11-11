<?php if ( ! overworld_edge_post_has_read_more() && ! post_password_required() ) { ?>
    <?php
	$additional_params = isset($additional_params) ? $additional_params : array();
    ?>
	<div class="edgtf-post-read-more-button">
		<?php
			$button_params = array(
				'type'         => 'simple',
				'link'         => get_the_permalink(),
				'text'         => esc_html__( 'Read More', 'overworld' ),
				'custom_class' => 'edgtf-blog-list-button',
				'icon_pack' => 'ion_icons',
				'ion_icon'  => 'ion-android-arrow-forward'
			);
		    $button_params = array_merge($button_params, $additional_params);
			
			echo overworld_edge_return_button_html( $button_params );
		?>
	</div>
<?php } ?>