<div class="edgtf-fullscreen-menu-holder-outer">
    <div class="edgtf-fullscreen-menu-holder">
        <div class="edgtf-fullscreen-menu-outer"></div>
		<?php if ( $fullscreen_menu_in_grid ) : ?>
        <div class="edgtf-container-inner">
			<?php endif; ?>

			<?php
			//Navigation
			overworld_edge_get_module_template_part( 'templates/full-screen-menu-navigation', 'header/types/header-minimal' );
			?>

			<?php if ( $fullscreen_menu_in_grid ) : ?>
        </div>
	<?php endif; ?>
    </div>
</div>