<div class="edgtf-tournament-timetable-holder">
	<?php
	if ( ! empty( $timetable_items ) ) {
		foreach ( $timetable_items as $timetable_item ) {
			$item_holder_class = empty($timetable_item['link']) ? 'edgtf-no-link' : 'edgtf-with-link'; ?>
			<div class="edgtf-tt-item-outer <?php echo esc_attr($item_holder_class); ?>">
				<div class="edgtf-tt-item-holder">
					<?php if (!empty($timetable_item['day'])) { ?>
						<div class="edgtf-tt-day edgtf-tt-section"><?php echo esc_html($timetable_item['day']); ?></div>
					<?php } ?>
					<?php if (!empty($timetable_item['message'])) { ?>
						<div class="edgtf-tt-message edgtf-tt-section"><?php echo esc_html($timetable_item['message']); ?></div>
					<?php } ?>
					<?php if (!empty($timetable_item['event_title'])) { ?>
						<div class="edgtf-tt-event-title edgtf-tt-section"><?php echo esc_html($timetable_item['event_title']); ?></div>
					<?php } ?>
					<?php if (!empty($timetable_item['link'])) { ?>
						<a itemprop="url" class="edgtf-tt-link" href="<?php echo esc_url( $timetable_item['link'] ); ?>" target="<?php echo esc_attr( $timetable_item['link_target'] ); ?>"></a>
					<?php } ?>
				</div>
			</div>
		<?php }
	} ?>
</div>