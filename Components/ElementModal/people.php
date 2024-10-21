<?php

namespace Flynt\Components\ElementModal;

use Flynt\FieldVariables;
use Timber\Timber;

add_action('wp_ajax_get_single_people', 'Flynt\Components\ElementModal\get_single_people');
add_action('wp_ajax_nopriv_get_single_people', 'Flynt\Components\ElementModal\get_single_people');

function get_single_people() {
    $post_slug = $_POST['slug'];

    if (empty($post_slug)) {
        wp_send_json_error(['message' => 'No post slug provided']);
    }

    $args = [
        'post_type' => 'people',
        'name' => sanitize_title($post_slug),
    ];

    $post = Timber::get_posts($args);

    if (empty($post)) {
        wp_send_json_error(['message' => 'No post found']);
    }

     // Fetch ACF fields
     $acf_fields = get_fields($post[0]->ID); // get_fields() fetches all ACF fields

     // Merge the ACF fields with the post data
     $context = [
         'post' => $post[0],
         'acf_fields' => $acf_fields
     ];
 
     // Render the Twig template with the post and its ACF fields
     Timber::render('Partials/_people.twig', $context);

    wp_die();
}