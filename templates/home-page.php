<?php
/*
Template Name: Home Template
*/

get_header();
?>

<main class="home-page">
    <div class="home-page__inner container">

        <h1 class="text-center p-2">Products List</h1>

        <?php
        $args = array(
            'post_type'        => 'product',
            'posts_per_page'   => -1,
        );

        $query = new WP_Query( $args );
        if ( $query->have_posts() ): ?>
            <div class="products-list row">
                <?php while ( $query->have_posts() ): $query->the_post(); ?>
                    <div class="products-list__item col-sm-6 col-md-4 col-lg-3">
                        <?php
                            $is_on_sale = get_post_meta(get_the_ID(), '_is_on_sale', true);
                            $product_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        ?>
                        <a href="<?php the_permalink(); ?>" class="products-list__item-content">
                            <?php if($is_on_sale == 'yes'): ?>
                                <span class="product-on-sale">Sale</span>
                            <?php endif; ?>

                            <figure class="product-image">
                                <img src="<?php echo esc_attr($product_image_url); ?>" alt="product image">
                            </figure>

                            <h4 class="product-title"><?php the_title(); ?></h4>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_query();
        ?>

    </div>
</main>

<?php get_footer(); ?>