<?php if (!empty($prize_pool) || !empty($play_mode) || !empty($platform) || !empty($players_number)) { ?>
    <div class="edgtf-tournament-meta">
        <?php if (!empty($prize_pool)) { ?>
            <div class="edgtf-tournament-meta-item">
                <div class="edgtf-tournament-meta-icon ion-cash"></div>
                <div class="edgtf-tournament-meta-info">
                    <?php echo esc_html($prize_pool); ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($play_mode)) { ?>
            <div class="edgtf-tournament-meta-item">
                <div class="edgtf-tournament-meta-icon ion-ios-game-controller-b"></div>
                <div class="edgtf-tournament-meta-info">
                    <?php echo esc_html($play_mode); ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($platform)) { ?>
            <div class="edgtf-tournament-meta-item">
                <div class="edgtf-tournament-meta-icon">
	                <?php $platform_logo_id = overworld_edge_get_attachment_id_from_url( $platform_logo );
					echo wp_get_attachment_image( $platform_logo_id, 'full' ); ?>
                </div>
                <div class="edgtf-tournament-meta-info">
                    <?php echo esc_html($platform); ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($players_number)) { ?>
            <div class="edgtf-tournament-meta-item">
                <div class="edgtf-tournament-meta-icon ion-android-people"></div>
                <div class="edgtf-tournament-meta-info">
                    <?php echo esc_html($players_number); ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>