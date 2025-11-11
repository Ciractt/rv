<?php if(comments_open()) { ?>
	<div class="edgtf-post-info-comments-holder">
		<a itemprop="url" class="edgtf-post-info-comments" href="<?php comments_link(); ?>">
			<?php comments_number('0 ' . esc_html__('Comments','overworld'), '1 '.esc_html__('Comment','overworld'), '% '.esc_html__('Comments','overworld') ); ?>
		</a>
	</div>
<?php } ?>