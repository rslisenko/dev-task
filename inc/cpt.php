<?php
/**
 * Register Product post type.
 */

include_once ('3rd-party/gallery-meta-box.php');

if ( !class_exists('ProductCPT')) {

    class ProductCPT {
        const post_type_slug = 'product';
        const post_type_taxonomy_slug = 'product-category';

        /* Class constructor */
        public function __construct() {
            add_action( 'init', [ __CLASS__, 'register_post_type' ] );
            add_action( 'init', [ __CLASS__, 'add_product_taxonomy' ] );
            add_action('add_meta_boxes', [ __CLASS__, 'add_product_meta'] );
            add_action('save_post', [ __CLASS__, 'save_product_meta' ] );
        }

        /* Method which registers the post type */
        public static function register_post_type() {
            $name 		= ucwords(self::post_type_slug);
            $plural 	= $name . 's';

            $labels = [
                'name' 					=> _x( $plural, 'post type general name' ),
                'singular_name' 		=> _x( $name, 'post type singular name' ),
                'add_new' 				=> _x( 'Add New', strtolower( $name ) ),
                'add_new_item' 			=> __( 'Add New ' . $name ),
                'edit_item' 			=> __( 'Edit ' . $name ),
                'new_item' 				=> __( 'New ' . $name ),
                'all_items' 			=> __( 'All ' . $plural ),
                'view_item' 			=> __( 'View ' . $name ),
                'search_items' 			=> __( 'Search ' . $plural ),
                'not_found' 			=> __( 'No ' . strtolower( $plural ) . ' found'),
                'not_found_in_trash' 	=> __( 'No ' . strtolower( $plural ) . ' found in Trash'),
                'parent_item_colon' 	=> '',
                'menu_name' 			=> $plural
            ];

            $args = [
                'label' 				=> $plural,
                'labels' 				=> $labels,
                'public' 				=> true,
                'show_ui' 				=> true,
                'show_in_rest'          => true,
                'menu_icon'             => 'dashicons-tablet',
                'supports' 				=> array( 'title', 'editor' , 'thumbnail' ),
                'show_in_nav_menus' 	=> true,
                '_builtin' 				=> false,
                'taxonomies'            => [ self::post_type_taxonomy_slug ]
            ];

            // Register the post type
            register_post_type( self::post_type_slug, $args );
        }

        /* attach the taxonomy to the post type */
        public static function add_product_taxonomy() {
            $name 		= 'Category';
            $plural 	= 'Categories';

            $args = [
                'labels' => [
                    'name'              => _x( $plural, 'taxonomy general name' ),
                    'singular_name'     => _x( $name, 'taxonomy singular name' ),
                    'search_items'      => __( 'Search' ),
                    'all_items'         => __( 'All' . $plural ),
                    'parent_item'       => __( 'Parent' . $name ),
                    'parent_item_colon' => __( 'Parent' . $name ),
                    'edit_item'         => __( 'Edit' . $name ),
                    'update_item'       => __( 'Update' . $name ),
                    'add_new_item'      => __( 'Add' . $name ),
                    'new_item_name'     => __( 'New' . $name ),
                    'menu_name'         => __( $name ),
                ],
                'hierarchical'          => true,
                'show_in_rest'          => true,
            ];

            register_taxonomy( self::post_type_taxonomy_slug, self::post_type_slug, $args );
        }

        /* Attach meta-box to the post type */
        public static function add_product_meta() {
            add_meta_box(
                'product_details_box',
                __('Product Details', 'textdomain'),
                [__CLASS__, 'product_details_box_content'],
                'product',
                'normal',
                'high'
            );
        }

        public static function product_details_box_content() {
            global $post;
            wp_nonce_field('product_box_action', 'product_box_content_nonce');

            $fields = get_post_custom($post->ID);

            $product_price = isset($fields['_product_price']) ? $fields['_product_price'][0] : 0;
            $product_sale_price = isset($fields['_product_sale_price']) ? $fields['_product_sale_price'][0] : 0;
            $product_video_link = isset($fields['_product_video_link']) ? $fields['_product_video_link'][0] : '';
            var_dump($product_video_link);
            $product_video_id = '';
            if ($product_video_link) {
                parse_str( parse_url( $product_video_link, PHP_URL_QUERY), $product_video_link_params );
                $product_video_id = $product_video_link_params['v'] ?? '';
            }
            $is_on_sale = isset($fields['_is_on_sale']) ? $fields['_is_on_sale'][0] : 'no';
            ($is_on_sale == 'yes') ? $check_mark = "checked" : $check_mark = "";

            echo '<table>';
                echo '<tr>';
                    echo '<td>';
                        echo '<label for="product-price" style="display: block;">'. _e('Price:', 'textdomain') . '</label>';
                        echo '<input type="number" id="product-price" name="_product_price" value="'. $product_price.'" placeholder="Product Price">';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<label for="product-sale-price" style="display: block;">'. _e('Sale Price:', 'textdomain') . '</label>';
                        echo '<input type="number" id="product-sale-price" name="_product_sale_price" value="'. $product_sale_price.'" placeholder="Product sale price">';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<input type="checkbox" id="is-on-sale" name="_is_on_sale" value="yes" ' . $check_mark.'/>';
                        echo '<label for="is-on-sale">'. _e('Is on sale?', 'textdomain') . '</label>';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<label for="video" style="display: block;">'. _e('Product Video Youtube Link:', 'textdomain') . '</label>';
                        echo '<input type="url" name="_product_video_link" value="'.$product_video_link.'" id="video"/>';
                        if (!empty($product_video_id)) {
                            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'. $product_video_id .'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display: block;"></iframe>';
                        } else {
                            echo '<p>Incorrect Link</p>';
                        }
                    echo '</td>';
                echo '</tr>';

            echo '</table>';

        }

        /* Listens for when the post type being saved */
        public static function save_product_meta($post_id) {

            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return;
            }

            if ( isset( $_REQUEST['product_box_content_nonce'] ) && !wp_verify_nonce($_POST['product_box_content_nonce'], 'product_box_action')) {
                return;
            }

            if ( !current_user_can('edit_post', $post_id) ) {
                return;
            }

            if ( isset($_POST['_product_price']) ) {
                $product_price = max( $_POST['_product_price'], 0 );
                update_post_meta($post_id, '_product_price', $product_price);
            }

            if ( isset($_POST['_product_sale_price']) ) {
                $product_sale_price = max( $_POST['_product_sale_price'], 0);
                update_post_meta($post_id, '_product_sale_price', $product_sale_price);
            }

            if ( isset($_POST['_product_video_link']) ) {
                $product_video_link = !empty( $_POST['_product_video_link'] ) ? esc_url_raw( wp_unslash($_POST['_product_video_link'])) : '' ;
                update_post_meta($post_id, '_product_video_link', $product_video_link);
            }

            if ( isset( $_POST['_is_on_sale'] )) {
                update_post_meta( $post_id, '_is_on_sale', $_POST['_is_on_sale'] );
            } else {
                update_post_meta( $post_id, '_is_on_sale', "no" );
            }

        }
    }
}

new ProductCPT();