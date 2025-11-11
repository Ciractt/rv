<?php
$match_bg_image    = overworld_edge_get_meta_field_intersect('match_bg_image' );
$match_bg_image_id = overworld_edge_get_attachment_id_from_url( $match_bg_image );

$background_image   = array();
$background_image[] = 'background-image: url( ' . OVERWORLD_CORE_ASSETS_URL_PATH . '/css/img/transparent.png )';

$background_image_url = wp_get_attachment_image_src( $match_bg_image_id, 'full' );
if ( ! empty( $background_image_url ) ) {
	$background_image[] = 'background-image: url( ' . esc_url( $background_image_url[0] ) . ')';
}

$background_image[] = 'background-repeat: no-repeat';
$background_image[] = 'background-position: top center';
$background_image[] = 'background-size: cover';
?>
<div class="edgtf-match-single-image-background" <?php overworld_edge_inline_style( $background_image ); ?>></div>
<?php overworld_core_get_cpt_single_module_template_part( 'templates/single/parts/info-top', 'match', '', $params ); ?>
