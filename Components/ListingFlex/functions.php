<?php

namespace Flynt\Components\ListingFlex;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
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

add_filter('acf/load_field/name=post_types', function ($field) {
    $args = array(
        'public' => true,
    );

    foreach (get_post_types($args, 'objects') as $post_type) {
        // don't list attachments or pages
        if ($post_type->name == 'attachment' || $post_type->name == 'page') {
            continue;
        }
        $field['choices'][$post_type->name] = $post_type->label;
    }

    // return the field
    return $field;
});

add_filter('acf/load_field/name=flexFilters', function ($field) {
    $args = array(
        'public' => true,
    );

    foreach (get_taxonomies($args, 'objects') as $tax) {
        if ($tax->name == 'post_format') {
            continue;
        }
        $field['choices'][$tax->name] = $tax->label;
    }

    // return the field
    return $field;
});

add_filter('acf/load_field/name=flexTaxonomies', function ($field) {
    $args = array(
        'public' => true,
    );

    foreach (get_taxonomies($args, 'objects') as $tax) {
        // don't list attachments or pages
        if ($tax->name == 'post_format') {
            continue;
        }
        $field['layouts'][] = [
            'name' => $tax->name,
            'label' => $tax->label,
            'sub_fields' => [
                array(
                    'key' => 'flexTax_' . $tax->name,
                    'label' => '',
                    'name' => $tax->name,
                    'aria-label' => '',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'relevanssi_exclude' => 0,
                    'taxonomy' => $tax->name,
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'return_format' => 'id',
                    'field_type' => 'checkbox',
                    'multiple' => 1,
                    'allow_null' => 0,
                ),
            ]
        ];
    }

    // return the field
    return $field;
});

// ajax action with a parameter
add_action('wp_ajax_nopriv_apply_filters_posts', 'Flynt\Components\ListingFlex\apply_filters_posts');
add_action('wp_ajax_apply_filters_posts', 'Flynt\Components\ListingFlex\apply_filters_posts');


function apply_filters_posts() {

    // get the submitted parameters
    $taxonomies = $_POST['taxonomies'];
    $post_types = $_POST['post_types'];
    $orderby = $_POST['orderby'];
    $order = $_POST['order'];
    $maxPosts = $_POST['maxPosts'];
    $labels = $_POST['labels'];
    $tax_query = $_POST['tax_query'];

    // // if data['post_types'] is array and longer than 1, set relation to OR else to AND
    if (is_array($post_types) && count($post_types) > 1) {
            $tax_query['relation'] = 'OR';
    } else {
        $tax_query['relation'] = 'AND';
    }

    $taxonomies['relation'] = 'AND';
    $pt = json_decode(stripslashes($post_types), true);

    $p = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $pt,
        'tax_query' => $taxonomies,
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => -1,
        'ignore_sticky_posts' => $maxPosts,
    ]);

    
    Timber::render('Partials/_items.twig', array('posts' => $p, 'labels' => json_decode(stripslashes($labels), true)));
    

    // return the result in JSON format
    // echo $post_types;

    // IMPORTANT: don't forget to "exit"
    wp_die();
}

function getACFLayout()
{
    return [
        'name' => 'ListingFlex',
        'label' => __('Listing: Flex', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Content Types', 'flynt'),
                'name' => 'post_types',
                'aria-label' => '',
                'type' => 'select',
                'required' => 1,
                'wrapper' => [
                    'width' => '100%',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 0,
                'return_format' => 'value',
                'multiple' => 1,
                'allow_null' => 0,
                'ui' => 1,
                'ajax' => 0,
                'placeholder' => '',
            ],
            array(
                'label' => 'Taxonomies to display',
                'name' => 'flexTaxonomies',
                'aria-label' => '',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => 'flexTaxonomiesSelector',
                ),
                'relevanssi_exclude' => 0,
                'hide_collapse' => 1,
                'collapse_all_flexible' => 0,
                'btn-icon-only' => 0,
                'min' => '',
                'max' => '',
                'button_label' => 'Add Pre-Filter',
            ),
            array(
                'label' => 'User Filters',
                'name' => 'flexFilters',
                'aria-label' => '',
                'type' => 'select',
                'required' => 0,
                'wrapper' => [
                    'width' => '100%',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 0,
                'return_format' => 'array',
                'multiple' => 1,
                'allow_null' => 0,
                'ui' => 1,
                'ajax' => 0,
                'placeholder' => '',
            ),
            [
                'label' => __('Max Number of Items', 'flynt'),
                'instructions' => __('Set to -1 for unlimited', 'flynt'),
                'name' => 'maxPosts',
                'type' => 'number',
                'default_value' => 3,
                'min' => -1,
                'step' => 1,
                'wrapper' => [
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'label' => 'Sort By',
                'name' => 'orderby',
                'aria-label' => '',
                'type' => 'select',
                'required' => 0,
                'wrapper' => [
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 0,
                'choices' => [
                    'title' => 'Alphabetical',
                    'date' => 'Publishing Date',
                    'relevance' => 'Relevance',
                    'rand' => 'Random',
                    'menu_order' => 'Custom Order',
                ],
                'default_value' => 'title',
                'return_format' => 'value',
                'multiple' => 0,
                'allow_null' => 0,
                'ui' => 0,
                'ajax' => 0,
                'placeholder' => '',
            ],
            array(
                'label' => 'Order',
                'name' => 'order',
                'aria-label' => '',
                'type' => 'button_group',
                'instructions' => '',
                'wrapper' => array(
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'ASC' => 'Asc.',
                    'DESC' => 'Desc.',
                ),
                'default_value' => 'ASC',
                'return_format' => 'value',
                'layout' => 'horizontal',
            ),
            array(
                'label' => 'Show "Load More"',
                'name' => 'show_load_more',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'wrapper' => array(
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ),
                'relevanssi_exclude' => 0,
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            array(
                'label' => '"View All"-Link',
                'name' => 'viewAllLink',
                'aria-label' => '',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'wrapper' => array(
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ),
                'relevanssi_exclude' => 0,
                'message' => '',
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
        ]
    ];
}

Options::addTranslatable('ListingFlex', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            // [
            //     'label' => __('All Posts', 'flynt'),
            //     'name' => 'allPosts',
            //     'type' => 'text',
            //     'default_value' => __('View all', 'flynt'),
            //     'required' => 0,
            //     'wrapper' => [
            //         'width' => 50
            //     ],
            // ],
            // [
            //     'label' => __('All Posts Link', 'flynt'),
            //     'name' => 'allPostsLink',
            //     'type' => 'link',
            //     'required' => 0,
            //     'wrapper' => [
            //         'width' => 50
            //     ],
            // ],
            [
                'label' => __('Read More', 'flynt'),
                'name' => 'readMore',
                'type' => 'text',
                'default_value' => __('Read More', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Reading Time - (20) min read', 'flynt'),
                'instructions' => __('%d is placeholder for number of minutes', 'flynt'),
                'name' => 'readingTime',
                'type' => 'text',
                'default_value' => __('%d min read', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ]
        ],
    ]
]);
