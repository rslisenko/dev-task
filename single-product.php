<?php
/**
 * Single Product
*/

get_header(); ?>

<div class="product">
    <div class="product__inner container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <div class="product__image">
                    <?php echo get_the_post_thumbnail(get_the_ID(), 'large'); ?>
                </div>

                <h1 class="product__title"><?php the_title(); ?></h1>

                <div class="product__description">
                    <?php the_content(); ?>
                    <?php if($product_video_id = get_post_meta(get_the_ID(), '_product_video_link', true)): ?>
                        <?php
                            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'. $product_video_id .'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display: block;"></iframe>';
                        ?>
                    <?php endif; ?>
                </div>

                <div class="product__price">
                    <?php if($price = get_post_meta(get_the_ID(), '_product_price', true)): ?>
                        <h3 class="regular-price">
                            Regular price:
                            <?php echo $price . '$'; ?>
                        </h3>
                    <?php endif; ?>

                    <?php if($sale_price = get_post_meta(get_the_ID(), '_product_sale_price', true)): ?>
                        <h3 class="sale-price">
                            Sale price:
                            <?php echo $sale_price . '$'; ?>
                        </h3>
                    <?php endif; ?>
                </div>

                <?php if($gallery_data = get_post_meta( get_the_ID(), 'gallery_data', true )): ?>
                    <div class="product__gallery">
                        <h3>Product images:</h3>

                        <div class="row">
                            <?php foreach($gallery_data['image_url'] as $gallery_item): ?>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <figure>
                                        <img src="<?php echo esc_attr($gallery_item); ?>" alt="image">
                                    </figure>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="product__related-products">

                    <h3>Related products:</h3>

                    <?php
                        $terms = get_the_terms( get_the_ID() , 'product-category' );
                        $product_categories = [];
                        foreach ( $terms as $term ) {
                            $product_categories[] = $term->slug;
                        }
                        $args = array(
                            'post_type' => 'product',
                            'posts_per_page' => 3,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product-category',
                                    'field'    => 'slug',
                                    'terms'    => $product_categories,
                                    'operator' => 'IN'
                                ),
                            ),
                        );

                        $related_products = new WP_Query( $args );

                        if ( $related_products->have_posts() ): ?>
                            <div class="products-list row">
                                <?php while ( $related_products->have_posts() ): $related_products->the_post(); ?>
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <a href="<?php the_permalink(); ?>">
                                            <figure>
                                                <?php the_post_thumbnail('large'); ?>
                                            </figure>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif;
                        wp_reset_postdata();
                    ?>

                </div>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php  get_footer(); ?>