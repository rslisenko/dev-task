<?php

/**
*   enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'enqueue_scripts_and_styles' );
function enqueue_scripts_and_styles() {
    $parenthandle = 'parent-style';
    $theme        = wp_get_theme();

    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', array(), $theme->parent()->get( 'Version' ));
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( $parenthandle ), $theme->get( 'Version' ));

}

/**
*   Disable admin bar for a user
 */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (is_user_logged_in()) {

        $user = wp_get_current_user();

        if ($user->data->user_login == 'wp-test') {
            add_filter('show_admin_bar', '__return_false');
        }

    }

}

