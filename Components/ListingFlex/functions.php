<?php

namespace Flynt\Components\ListingFlex;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=ListingFlex', function ($data) {

    $data['taxonomies'] = $data['taxonomies'] ?: [];

    $data['posts'] = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $data['post_types'],
        'orderby' => $data['orderby'],
        'order' => $data['order'],
        'category' => join(',', array_map(function ($taxonomy) {
            return $taxonomy->term_id;
        }, $data['taxonomies'])),
        'posts_per_page' => $data['maxPosts'],
        'ignore_sticky_posts' => 1,
        'post__not_in' => array(get_the_ID())
    ]);

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
