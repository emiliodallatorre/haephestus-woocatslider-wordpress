<?php
/**
 * Plugin Name: Woocommerce Category Slider
 * Plugin URI: https://github.com/emiliodallatorre/haephestus-woocatslider-wordpress
 * Description: A simple product-by-category slider for WooCommerce.
 * Version: 0.1
 * Author: Emilio Dalla Torre
 * Author URI: https://emiliodallatorre.it
 **/

/*
 * Create a shortcode that accepts a category slug as a parameter
 * that is able to provide you with a slider of products from that
 * category.
 */


function enqueue_my_style()
{
    // Register your stylesheet (if not already registered)
    wp_register_style('my-style', plugin_dir_url(__FILE__) . 'style.css', array(), '1.0', 'all');

    // Enqueue the stylesheet
    wp_enqueue_style('my-style');
}

add_action('wp_enqueue_scripts', 'enqueue_my_style');

function enqueue_my_script()
{
    // Register your script (if not already registered)
    wp_register_script('my-script', plugin_dir_url(__FILE__) . 'woocatslider.js', array('jquery'), '1.0', true);

    // Enqueue the script
    wp_enqueue_script('my-script');
}

add_action('wp_enqueue_scripts', 'enqueue_my_script');

function woocatslider_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'category' => '',
    ), $atts, 'woocatslider');

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        'product_cat' => $atts['category'],
    );

    $loop = new WP_Query($args);


    if ($loop->have_posts()) {
        $output =
            '
        <div class="slideshow-container">
        ';

        while ($loop->have_posts()) : $loop->the_post();
            global $product;
            $output .= '<div class="woocatslider-item slide">';
            $output .= '<a href="' . get_permalink() . '">';
            $output .= get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<span class="price">' . $product->get_price_html() . '</span>';
            // $output .= '<a href="' . $product->add_to_cart_url() . '" rel="nofollow" data-product_id="' . $product->id . '" class="button add_to_cart_button product_type_simple">' . __('Add to cart') . '</a>';
            $output .= '</a>';
            $output .= '</div>';
        endwhile;
        $output .= '        
            <button class="prev" onclick="changeSlide(-1)">❮</button>
            <button class="next" onclick="changeSlide(1)">❯</button>
        </div>';
    } else {
        $output = __('No products found');
    }
    wp_reset_postdata();
    return $output;
}

add_shortcode('woocatslider', 'woocatslider_shortcode');

/*
 * Example:
 * [woocatslider category="clothing"]
 */