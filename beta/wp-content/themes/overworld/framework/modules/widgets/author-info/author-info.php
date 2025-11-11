<?php

if ( class_exists( 'OverworldCoreClassWidget' ) ) {
	class OverworldEdgeClassAuthorInfoWidget extends OverworldCoreClassWidget {
		public function __construct() {
			parent::__construct(
				'edgtf_author_info_widget',
				esc_html__( 'Overworld Author Info Widget', 'overworld' ),
				array( 'description' => esc_html__( 'Add author info element to widget areas', 'overworld' ) )
			);
			
			$this->setParams();
		}
		
		protected function setParams() {
			$this->params = array(
				array(
					'type'  => 'textfield',
					'name'  => 'extra_class',
					'title' => esc_html__( 'Custom CSS Class', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'widget_bottom_margin',
					'title' => esc_html__( 'Widget Bottom Margin (px)', 'overworld' )
				),
				array(
					'type'  => 'textfield',
					'name'  => 'author_username',
					'title' => esc_html__( 'Author Username', 'overworld' )
				),
				array(
					'type'  => 'image',
					'name'  => 'big_image',
					'title' => esc_html__( 'Big Image', 'overworld' )
				),
			);
		}
		
		public function widget( $args, $instance ) {
			extract( $args );
			
			$extra_class = '';
			if ( ! empty( $instance['extra_class'] ) ) {
				$extra_class = $instance['extra_class'];
			}
			
			$widget_styles = array();
			if ( isset( $instance['widget_bottom_margin'] ) && $instance['widget_bottom_margin'] !== '' ) {
				$widget_styles[] = 'margin-bottom: ' . overworld_edge_filter_px( $instance['widget_bottom_margin'] ) . 'px';
			}
			
			$authorID = 1;
			if ( ! empty( $instance['author_username'] ) ) {
				$author = get_user_by( 'login', $instance['author_username'] );
				
				if ( $author ) {
					$authorID = $author->ID;
				}
			}
			
			$author_info = get_the_author_meta( 'description', $authorID );
			?>
			
			<div class="widget edgtf-author-info-widget <?php echo esc_attr( $extra_class ); ?>" <?php echo overworld_edge_get_inline_style( $widget_styles ); ?>>
				<div class="edgtf-aiw-inner">
					<a itemprop="url" class="edgtf-aiw-image" href="<?php echo esc_url( get_author_posts_url( $authorID ) ); ?>">
						<?php echo overworld_edge_kses_img( get_avatar( $authorID, 368 ) ); ?>
					</a>
					<div class="edgtf-author-info-additional">
						<?php if ( $author_info !== "" ) { ?>
							<h3 class="edgtf-aiw-title"><?php esc_html_e( 'About The Author', 'overworld' ); ?></h3>
							<p itemprop="description" class="edgtf-aiw-text"><?php echo wp_kses_post( $author_info ); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}