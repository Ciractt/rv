<?php
$date = get_post_meta( $tournament_id, 'edgtf_tournament_date_meta', true );
$time = get_post_meta( $tournament_id, 'edgtf_tournament_time_meta', true );

$dateobj = date_create_from_format( 'Y-m-d', $date );

$date = '';
if ( $dateobj ) {
	$date = date_format( $dateobj, 'jS F Y' );
} ?>

<div class="edgtf-tournament-top-info edgtf-tournament-section">
	<?php if ( ! empty( $date ) ) { ?>
		<div class="edgtf-tournament-date"><?php echo esc_html( $date ) ?></div><?php } ?>
	<?php if ( ! empty( $time ) ) { ?>
		<div class="edgtf-tournament-time"><?php echo esc_html( $time ) ?></div><?php } ?>
	<?php echo overworld_core_get_cpt_shortcode_module_template_part( 'tournament', 'single-tournament', 'categories', '', $params ); ?>
</div>