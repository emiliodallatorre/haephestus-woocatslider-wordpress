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

defined('ABSPATH') or die('No script kiddies please!');


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
        'posts_per_page' => 18,

        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_key' => 'total_sales',
    );

    $for_category = false;
    if ($atts['category'] != null) {
        $args['product_cat'] = $atts['category'];

        $for_category = true;
    } else {
        $args['orderby'] = 'date';
    }

    $loop = new WP_Query($args);
    $category = $atts['category'];


    if ($loop->have_posts()) {
        $output =
            '
        <div class="category-slider-container show-only-mobile">
            <div class="slideshow-container">
            ';

        while ($loop->have_posts()) : $loop->the_post();
            global $product;
            $output .= sprintf('<div class="woocatslider-item slide category-%s">', $category);
            $output .= '<a href="' . get_permalink() . '">';
            $output .= get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<span class="price">' . $product->get_price_html() . '</span>';
            // $output .= '<a href="' . $product->add_to_cart_url() . '" rel="nofollow" data-product_id="' . $product->id . '" class="button add_to_cart_button product_type_simple">' . __('Add to cart') . '</a>';
            $output .= '</a>';
            $output .= '</div>';
        endwhile;
        $output .= '        
                <button class="prev" onclick=\'changeSlide(-1, "' . trim($category) . '" )\'>❮</button>
                <button class="next" onclick=\'changeSlide(1, "' . trim($category) . '" )\'>❯</button>
            </div>';

        // Add see more button to go to the category
        if ($for_category) {
            $output .= '<a href="' . get_term_link($category, 'product_cat') . '" class="button">' . __('Vedi altro') . '</a>';
        }

        $output .= '</div>';
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