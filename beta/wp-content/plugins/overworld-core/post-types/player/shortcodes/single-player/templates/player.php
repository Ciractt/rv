<div class="edgtf-player edgtf-item-space">
	<div class="edgtf-player-inner">
	    <?php if (get_the_post_thumbnail($player_id) !== '') { ?>
	        <div class="edgtf-player-image">
	            <?php echo get_the_post_thumbnail($player_id); ?>
	            <div class="edgtf-player-info">
	                <h4 itemprop="name" class="edgtf-player-name entry-title"><?php echo esc_html(get_the_title($player_id)); ?></h4>
	                <?php if (!empty($nickname)) { ?>
	                    <h6 class="edgtf-player-nickname"><?php echo esc_html($nickname); ?></h6>
	                <?php } ?>
	            </div>
	            <a class="edgtf-player-overlay-link" itemprop="url" href="<?php echo esc_url(get_the_permalink($player_id)); ?>" title="<?php echo esc_attr(get_the_title($player_id)); ?>"></a>
	        </div>
	    <?php } ?>
	</div>
</div>