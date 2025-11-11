<?php
$id = get_the_ID();

$date = get_post_meta( $id, 'edgtf_tournament_date_meta', true );
$time = get_post_meta( $id, 'edgtf_tournament_time_meta', true );

$dateobj = date_create_from_format( 'Y-m-d', $date );

$date = '';
if ( $dateobj ) {
	$date = date_format( $dateobj, 'jS F Y' );
} ?>

<div class="edgtf-tournament-date-time-location edgtf-tournament-section">
	<?php if ( ! empty( $date ) ) { ?>
		<div class="edgtf-tournament-date"><?php echo esc_html( $date ) ?></div><?php } ?>
	<?php if ( ! empty( $time ) ) { ?>
		<div class="edgtf-tournament-time"><?php echo esc_html( $time ) ?></div><?php } ?>
	<?php if ( ! empty( $location ) ) { ?>
		<div class="edgtf-tournament-location"><?php echo esc_html( $location ) ?></div><?php } ?>
</div>