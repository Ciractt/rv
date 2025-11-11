<?php
$id = get_the_ID();

$date = get_post_meta( $id, 'edgtf_match_date_meta', true );
$time = get_post_meta( $id, 'edgtf_match_time_meta', true );

$dateobj = date_create_from_format( 'Y-m-d', $date );

$date = '';
if ( $dateobj ) {
	$date = date_format( $dateobj, 'jS F Y' );
}
if ( ! empty( $date ) ) { ?>
	<div class="edgtf-match-date"> <?php echo esc_attr( $date ) ?>, <?php echo esc_attr( $time ) ?></div><?php } ?>