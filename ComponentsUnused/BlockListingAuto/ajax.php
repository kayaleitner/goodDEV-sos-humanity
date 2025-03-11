<?php

namespace Flynt\Components\BlockListingAuto;

use Timber\Timber;

// ajax action with a parameter
add_action('wp_ajax_nopriv_apply_filters_posts', 'Flynt\Components\BlockListingAuto\apply_filters_posts');
add_action('wp_ajax_apply_filters_posts', 'Flynt\Components\BlockListingAuto\apply_filters_posts');

function apply_filters_posts()
{

    // get the submitted parameters
    $tax_query = $_POST['tax_query'];
    $post_types = $_POST['post_types'];
    $orderby = $_POST['orderby'];
    $order = $_POST['order'];
    $maxPosts = $_POST['maxPosts'];
    $labels = $_POST['labels'];

    // // if data['post_types'] is array and longer than 1, set relation to OR else to AND
    if (is_array($post_types) && count($post_types) > 1) {
            $tax_query['relation'] = 'OR';
    } else {
        $tax_query['relation'] = 'AND';
    }

    $tax_query['relation'] = 'AND';
    $pt = json_decode(stripslashes($post_types), true);

    $p = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $pt,
        'tax_query' => $tax_query,
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $maxPosts,
    ]);

    Timber::render('Partials/_items.twig', ['posts' => $p, 'labels' => json_decode(stripslashes($labels), true)]);

    wp_die();
}

// ajax action load more
add_action('wp_ajax_nopriv_load_more_posts', 'Flynt\Components\BlockListingAuto\load_more_posts');
add_action('wp_ajax_load_more_posts', 'Flynt\Components\BlockListingAuto\load_more_posts');

function load_more_posts()
{

    $orderby = $_POST['orderby'];
    $order = $_POST['order'];
    $maxPosts = $_POST['maxPosts'];
    $labels = $_POST['labels'];
    $count = $_POST['count'];

    $pt = $_POST['post_types'];
    $post_types = json_decode(stripslashes($pt), true);

    $filter_query = $_POST['filter_query'];
    $tax_query['relation'] = 'AND';

    if ($_POST['tax_query'] == 'null') {
        $tax_query = json_decode(stripslashes($filter_query), true);
    } else {
        $tax_query = $_POST['tax_query'];
    }

    $labels = $_POST['labels'];

    $posts = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $post_types,
        'tax_query' => $tax_query,
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $maxPosts,
        'offset' => $count,
    ]);

    Timber::render(
        'Partials/_items.twig',
        ['posts' => $posts, 'labels' => json_decode(stripslashes($labels), true)]
    );

    wp_die();
}
