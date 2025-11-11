<div <?php overworld_edge_class_attribute($link_list_classes); ?>>
	<?php foreach ( $links as $object ) {
		$link_exists = ! empty( $object['link'] ); ?>
        <div class="edgtf-link-item <?php if ( ! $link_exists ) echo esc_attr( 'edgtf-no-link' ); ?>" <?php overworld_edge_inline_style( $links_styles ); ?>>
	        <div class="edgtf-link-item-inner">
		        <?php if ( ! empty( $object['image'] ) ) { ?>
	                <div class="edgtf-link-item-image">
		                <?php echo wp_get_attachment_image( $object['image'], 'full' ); ?>
	                </div>
	            <?php } ?>
		        <?php if ( ! empty( $object['text'] ) ) { ?>
	                <div class="edgtf-link-item-text">
		                <?php echo esc_html( $object['text'] ); ?>
	                </div>
	            <?php } ?>
		        <?php if ( ! empty( $object['link'] ) ) { ?>
			        <a itemprop="url" class="edgtf-link-item-link" href="<?php echo esc_url( $object['link'] ); ?>" target="<?php echo esc_attr( $object['target'] ); ?>"></a>
			    <?php } ?>
	        </div>
        </div>
    <?php } ?>
</div>