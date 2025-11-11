<div class="edgtf-player-single-info-top">
	<div class="edgtf-player-single-info-top-inner">
		<?php echo get_the_post_thumbnail(); ?>
		<div class="edgtf-player-single-info-top-details">
			<?php if(!empty($nickname)) { ?>
				<h2 itemprop="name" class="edgtf-nickname entry-title">
					<?php echo esc_html($nickname); ?>
				</h2>
			<?php } ?>
			<h6 itemprop="name" class="edgtf-name entry-title"><?php the_title(); ?></h6>
		</div>
	</div>
</div>