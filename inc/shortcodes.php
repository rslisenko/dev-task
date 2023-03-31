<?php

function get_product_func($atts) {
    $a = shortcode_atts( array(
        'id' => '',
        'bg' => '#ffffff',
    ), $atts );

    $id = $a['id'];
    $bg = $a['bg'];

    ob_start(); ?>

    <?php if( ! empty($id) && get_post($id) ): ?>
        <a href="<?php echo get_the_permalink($id); ?>" class="custom-product" style="background-color: <?php echo $bg; ?>">

            <div class="custom-product__image">
                <?php echo get_the_post_thumbnail($id, 'large'); ?>
            </div>

            <div class="custom-product__title text-center">
                <h3><?php echo get_the_title($id); ?></h3>
            </div>

        </a>
    <?php endif; ?>

    <?php return ob_get_clean();
}
add_shortcode('get_product', 'get_product_func');