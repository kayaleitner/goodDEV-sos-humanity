<?php

namespace Flynt\Components\BlockListingAuto;

use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockListingAuto', function ($data) {

    $data['flexTaxonomies'] = $data['flexTaxonomies'] ?: [];

    // Create an empty array to store the tax query parameters
    $tax_query = [];

    // if data['post_types'] is array and longer than 1, set relation to OR else to AND
    if (is_array($data['post_types']) && count($data['post_types']) > 1) {
        $tax_query['relation'] = 'OR';
    } else {
        $tax_query['relation'] = 'AND';
    }

    $flexFiltersAsArray = array();
    $data['filters'] = [];
    foreach ($data['flexFilters'] as $filter) {

        $flexFiltersAsArray[] = $filter['value'];
    }


    foreach ($data['flexTaxonomies'] as $tax) {
        $tax_query[] = [
            'taxonomy' => $tax["acf_fc_layout"],
            'field'    => 'term_id',
            'terms'    => array_map(function ($item){
                return $item->term_id;
            }, $tax[""]),
        ];

        if (in_array($tax["acf_fc_layout"], $flexFiltersAsArray)) {
            $label = '';
            foreach($data['flexFilters'] as $filter) {
                if ($filter['value'] == $tax["acf_fc_layout"]) {
                    $label = $filter['label'];
                    break;
                }
            }
            $data['filters'][] = [
                'name' => $tax["acf_fc_layout"],
                'label' => $label,
                'terms' => get_terms([
                    'taxonomy' => $tax["acf_fc_layout"],
                    'hide_empty' => true,
                    'include' => array_map(function ($item){
                        return $item->ID;
                    }, $tax[""]),
                ]),
            ];
            
            $flexFiltersAsArray = array_filter($flexFiltersAsArray, function ($item) use ($tax) {
                return $item != $tax["acf_fc_layout"];
            });
        }
       
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
        'post__not_in' => [get_the_ID()]
    ]);

    $data['found_posts'] = $data['posts']->found_posts;

    // get all terms for taxonomies in flexFilters array

    foreach ($data['flexFilters'] as $filter) {
        if (in_array($filter['value'], $flexFiltersAsArray)) {
            $data['filters'][] = [
                'name' => $filter['value'],
                'label' => $filter['label'],
                'terms' => get_terms([
                    'taxonomy' => $filter['value'],
                    'hide_empty' => true,
                ]),
            ];
        }
       // if filter value is category then if there is a prefilter category set, use all categories selected else use all categories
    }

    return $data;
});
