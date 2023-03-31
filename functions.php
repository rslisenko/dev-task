<?php

// required modules
require_once('inc/cpt.php');
include_once('inc/shortcodes.php');
include_once('inc/custom-endpoints.php');

/**
*   enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'enqueue_scripts_and_styles' );
function enqueue_scripts_and_styles() {
    $parenthandle = 'parent-style';
    $theme        = wp_get_theme();

    // styles
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', array(), $theme->parent()->get( 'Version' ));
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), $theme->get( 'Version' ));
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/assets/dist/css/main.min.css', array(), $theme->get( 'Version' ));

    // scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js', '', null, true);
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/dist/js/main.min.js', '', null, true);
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