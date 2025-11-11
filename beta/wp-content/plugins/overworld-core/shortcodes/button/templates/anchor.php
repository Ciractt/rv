<a itemprop="url" href="<?php echo esc_url($link); ?>" target="<?php echo esc_attr($target); ?>" <?php overworld_edge_inline_style($button_styles); ?> <?php overworld_edge_class_attribute($button_classes); ?> <?php echo overworld_edge_get_inline_attrs($button_data); ?> <?php echo overworld_edge_get_inline_attrs($button_custom_attrs); ?>>
    <?php if ($params['icon_position'] === 'left') {
	    echo overworld_edge_icon_collections()->renderIcon($icon, $icon_pack);
    } ?>
    <span class="edgtf-btn-text"><?php echo esc_html($text); ?></span>
	<?php if ($params['icon_position'] !== 'left') {
	    echo overworld_edge_icon_collections()->renderIcon( $icon, $icon_pack );
    } ?>
</a>