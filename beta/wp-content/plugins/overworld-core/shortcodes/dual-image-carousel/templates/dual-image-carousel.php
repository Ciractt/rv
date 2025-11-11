<div <?php overworld_edge_class_attribute($dualImagesCarouselClasses); ?> <?php echo overworld_edge_get_inline_attrs($data_params); ?> <?php overworld_edge_inline_style( $dualImagesCarouselStyle ); ?> >
    <div class="swiper-wrapper">
        <?php foreach ($dual_image_carousel as $object) : ?>
            <div class="swiper-slide">
                <?php if (!empty($object['background_image'])) { ?>
                    <div class="edgtf-slide-background-image-holder">
                        <div class="edgtf-slide-background-image">
                            <?php echo wp_get_attachment_image($object['background_image'], 'full'); ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($object['foreground_image'])) { ?>
                    <div class="edgtf-slide-foreground-image-holder">
                        <div class="edgtf-slide-foreground-image" data-swiper-parallax="-50%">
                            <?php echo wp_get_attachment_image($object['foreground_image'], 'full'); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="swiper-navigation">
        <span class="edgtf-swiper-button-prev edgtf-swiper-button">
	        <span class="edgtf-icon ion-ios-arrow-back"></span>
	        <span class="edgtf-text"> / Prev</span>
        </span>
	    <span class="edgtf-swiper-button-next edgtf-swiper-button">
		    <span class="edgtf-icon ion-ios-arrow-forward"></span>
		    <span class="edgtf-text">Next / </span>
	    </span>
    </div>

</div>