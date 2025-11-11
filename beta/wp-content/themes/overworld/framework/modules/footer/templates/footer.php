<?php do_action( 'overworld_edge_action_before_footer_content' ); ?>
</div> <!-- close div.content_inner -->
	</div>  <!-- close div.content -->
		<?php if($display_footer && ($display_footer_top || $display_footer_bottom)) { ?>
			<footer class="edgtf-page-footer <?php echo esc_attr($holder_classes); ?>">
				<?php
					if($display_footer_top) {
						overworld_edge_get_footer_top();
					}
					if($display_footer_bottom) {
						overworld_edge_get_footer_bottom();
					}
				?>
			</footer>
		<?php } ?>
	</div> <!-- close div.edgtf-wrapper-inner  -->
</div> <!-- close div.edgtf-wrapper -->
<?php
/**
 * overworld_edge_action_before_closing_body_tag hook
 *
 * @see overworld_edge_get_side_area() - hooked with 10
 * @see overworld_edge_smooth_page_transitions() - hooked with 10
 */
do_action( 'overworld_edge_action_before_closing_body_tag' ); ?>
<?php wp_footer(); ?>
</body>
</html>