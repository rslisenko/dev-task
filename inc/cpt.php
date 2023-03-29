<?php

add_action( 'init', 'product_CPT_init' );
/**
 * Register Product post type.
 */
function product_CPT_init() {
    $postLabels = [
        'name'               => 'Product',
        'singular_name'      => 'Product',
        'menu_name'          => 'Products',
        'name_admin_bar'     => 'Product',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Product',
        'new_item'           => 'New Product',
        'edit_item'          => 'Edit Product',
        'view_item'          => 'View Product',
        'all_items'          => 'All Products',
        'search_items'       => 'Search Products',
        'parent_item_colon'  => 'Parent Products:',
        'not_found'          => 'No Products found.',
        'not_found_in_trash' => 'No Products found in Trash.',
    ];

    $args = [
        'labels'             => $postLabels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_rest'       => true,
        'label'              => 'Products',
        'menu_icon'          => 'dashicons-tablet',
        'menu_position'      => null,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'location-category', 'type-category'],
        'taxonomies'          => ['product-category']
    ];

    register_post_type( 'product', $args );
}

function product_category() {
    $args = array(
        'labels' => array(
            'name'              => _x( 'Category', 'taxonomy general name' ),
            'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
            'search_items'      => __( 'Search' ),
            'all_items'         => __( 'All Categories' ),
            'parent_item'       => __( 'Parent category' ),
            'parent_item_colon' => __( 'Parent category' ),
            'edit_item'         => __( 'Edit category' ),
            'update_item'       => __( 'Update category' ),
            'add_new_item'      => __( 'Add category' ),
            'new_item_name'     => __( 'New category' ),
            'menu_name'         => __( 'Category' ),
        ),
    );
    register_taxonomy( 'product-category', 'product', $args );
}
add_action( 'init', 'product_category');