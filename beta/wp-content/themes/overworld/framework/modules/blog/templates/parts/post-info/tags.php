<?php
$tags = get_the_tags();
?>
<?php if($tags) { ?>
<div class="edgtf-tags-holder">
    <h5><?php echo esc_html( 'Tags:', 'overworld' ) ?></h5>
    <div class="edgtf-tags">
        <?php the_tags('', ' / ', ''); ?>
    </div>
</div>
<?php } ?>