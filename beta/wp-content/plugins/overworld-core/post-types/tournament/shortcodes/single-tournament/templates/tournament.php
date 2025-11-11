<div class="edgtf-tournament edgtf-item-space <?php echo esc_attr( $holder_classes ) ?>">
	<div class="edgtf-tournament-inner">
        <div class="edgtf-tournament-image">
            <?php if ( $bg_image ) {
            	$bg_image_id = overworld_edge_get_attachment_id_from_url( $bg_image );
				echo wp_get_attachment_image( $bg_image_id, 'full' );
			} else { ?>
				<img itemprop="image" width="1920" height="600" src="<?php echo OVERWORLD_CORE_ASSETS_URL_PATH . '/css/img/transparent.png'; ?>" alt=""/>
			<?php } ?>
            <div class="edgtf-tournament-info">
	            <?php echo overworld_core_get_cpt_shortcode_module_template_part('tournament', 'single-tournament', 'top-info', '', $params); ?>
                <<?php echo esc_attr($title_tag); ?> itemprop="name" class="edgtf-tournament-name entry-title">
	                <?php echo esc_html(get_the_title($tournament_id)); ?>
                </<?php echo esc_attr($title_tag); ?>>
                <?php echo overworld_core_get_cpt_shortcode_module_template_part('tournament', 'single-tournament', 'bottom-info', '', $params); ?>
            </div>
            <a class="edgtf-tournament-overlay-link" itemprop="url" href="<?php echo esc_url(get_the_permalink($tournament_id)); ?>" title="<?php echo esc_attr(get_the_title($tournament_id)); ?>"></a>
        </div>
	</div>
</div>