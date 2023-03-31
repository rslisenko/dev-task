<?php

add_action('rest_api_init', function () {
    register_rest_route( 'get-products-by-category-id/v1', '/(?P<category_id>\d+)',array(
        'methods'  => 'GET',
        'callback' => 'get_latest_products_by_category'
    ));
});

function get_latest_products_by_category($request) {

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product-category',
                'field'    => 'id',
                'terms'    => [$request['category_id']],
                'operator' => 'IN'
            ),
        ),
    );

    $products = get_posts( $args );

    $data = [];

    foreach ($products as $product) {
        $data[]['id'] = $product->ID;
        $data[]['description'] = $product->post_content;
        $data[]['image'] = get_the_post_thumbnail_url($product->ID);
        $data[]['price'] = get_post_meta($product->ID, '_product_price', true);
        $data[]['sale_price'] = get_post_meta($product->ID, '_product_sale_price', true);
        $data[]['is_on_sale'] = get_post_meta($product->ID, '_is_on_sale', true);
    }

    if (empty($products)) {
        return new WP_Error( 'empty_category', 'There are no products to display', array('status' => 404) );

    }

    return $data;

}