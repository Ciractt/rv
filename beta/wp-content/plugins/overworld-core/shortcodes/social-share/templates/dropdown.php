<div class="edgtf-social-share-holder edgtf-dropdown <?php echo esc_attr( $dropdown_class ); ?>">
	<a class="edgtf-social-share-dropdown-opener" href="javascript:void(0)">
		<?php if ( ! empty( $title ) ) { ?>
			<span class="edgtf-social-share-title"><?php echo esc_html( $title ); ?></span>
		<?php } ?>
		<i class="ion-android-share-alt"></i>
	</a>
	<div class="edgtf-social-share-dropdown">
		<ul>
			<?php foreach ( $networks as $net ) {
				echo wp_kses( $net, array(
					'li'   => array(
						'class' => true
					),
					'a'    => array(
						'itemprop' => true,
						'class'    => true,
						'href'     => true,
						'target'   => true,
						'onclick'  => true
					),
					'img'  => array(
						'itemprop' => true,
						'class'    => true,
						'src'      => true,
						'alt'      => true
					),
					'span' => array(
						'class' => true
					)
				) );
			} ?>
		</ul>
	</div>
</div>