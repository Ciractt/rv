<div class="edgtf-section-title-holder <?php echo esc_attr( $holder_classes ); ?>" <?php echo overworld_edge_get_inline_style( $holder_styles ); ?>>
	<div class="edgtf-st-inner">
		<?php if ( ! empty( $title ) ) { ?>
			<div class="edgtf-st-title-holder">
				<<?php echo esc_attr( $title_tag ); ?> class="edgtf-st-title" <?php echo overworld_edge_get_inline_style( $title_styles ); ?>>
					<?php echo wp_kses( $title, array( 'br' => true, 'span' => array( 'class' => true ) ) ); ?>
				</<?php echo esc_attr( $title_tag ); ?>>
				<?php if ( $title_type === 'text-decorated' && ! empty( $text_for_decoration ) ) { ?>
					<div class="edgtf-st-title-text-decoration"><span class="edgtf-prefix">#</span><?php echo esc_html($text_for_decoration); ?></div>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<<?php echo esc_attr( $text_tag ); ?> class="edgtf-st-text" <?php echo overworld_edge_get_inline_style( $text_styles ); ?>>
				<?php echo wp_kses( $text, array( 'br' => true ) ); ?>
			</<?php echo esc_attr( $text_tag ); ?>>
		<?php } ?>
		<?php if ( ! empty( $button_parameters ) ) { ?>
			<div class="edgtf-st-button"><?php echo overworld_edge_get_button_html( $button_parameters ); ?></div>
		<?php } ?>
	</div>
</div>