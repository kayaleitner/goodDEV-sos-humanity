<?php

namespace Flynt\Components\ListingFlex;

use Timber\Timber;

add_filter('Flynt/addComponentData?name=ListingFlex', function ($data) {

    $data['flexTaxonomies'] = $data['flexTaxonomies'] ?: [];

    // Create an empty array to store the tax query parameters
    $tax_query = array();

    // if data['post_types'] is array and longer than 1, set relation to OR else to AND
    if (is_array($data['post_types']) && count($data['post_types']) > 1) {
        $tax_query['relation'] = 'OR';
    } else {
        $tax_query['relation'] = 'AND';
    }

    foreach ($data['flexTaxonomies'] as $tax) {
        $tax_query[] = array(
            'taxonomy' => $tax["acf_fc_layout"],
            'field'    => 'term_id',
            'terms'    => $tax[""],
        );
    }

    $data['tax_query'] = $tax_query;

    $data['posts'] = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $data['post_types'],
        'orderby' => $data['orderby'],
        'order' => $data['order'],
        'tax_query' => $tax_query,
        'posts_per_page' => $data['maxPosts'],
        'ignore_sticky_posts' => 1,
        'post__not_in' => array(get_the_ID())
    ]);

    $data['found_posts'] = $data['posts']->found_posts;

    // get all terms for taxonomies in flexFilters array
    $data['filters'] = [];
    foreach ($data['flexFilters'] as $filter) {
        $data['filters'][] = [
            'name' => $filter['value'],
            'label' => $filter['label'],
            'terms' => get_terms([
                'taxonomy' => $filter['value'],
                'hide_empty' => true,
            ]),
        ];
    }

    return $data;
});