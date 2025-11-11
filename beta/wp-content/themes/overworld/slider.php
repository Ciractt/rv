<?php
do_action( 'overworld_edge_action_before_slider_action' );

$edgtf_slider_shortcode            = get_post_meta( overworld_edge_get_page_id(), 'edgtf_page_slider_meta', true );
$edgtf_slider_shortcode_top_offset = get_post_meta( overworld_edge_get_page_id(), 'edgtf_page_slider_top_offset_meta', true );

$slider_styles = array();

if ( $edgtf_slider_shortcode_top_offset !== '' ) {
	if (
		! overworld_edge_string_ends_with( $edgtf_slider_shortcode_top_offset, 'px' ) &&
		! overworld_edge_string_ends_with( $edgtf_slider_shortcode_top_offset, '%' )
	) {
		$edgtf_slider_shortcode_top_offset .= 'px';
	}

	$slider_styles[] = 'padding-top: ' . esc_attr( $edgtf_slider_shortcode_top_offset );
}

if ( ! empty( $edgtf_slider_shortcode ) ) { ?>
	<div class="edgtf-slider" <?php echo overworld_edge_get_inline_style( $slider_styles ); ?>>
		<div class="edgtf-slider-inner">
			<?php echo do_shortcode( wp_kses_post( $edgtf_slider_shortcode ) ); // XSS OK ?>
		</div>
	</div>
<?php }

do_action( 'overworld_edge_action_after_slider_action' );
?>