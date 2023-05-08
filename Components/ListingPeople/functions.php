<?php

namespace Flynt\Components\ListingPeople;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'people';

add_filter('Flynt/addComponentData?name=ListingPeople', function ($data) {
    $postType = POST_TYPE;

    $data['taxonomies'] = $data['taxonomies'] ?: [];

    $data['posts'] = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $postType,
        'orderby' => $data['orderby'],
        'order' => $data['order'],
        'category' => join(',', array_map(function ($taxonomy) {
            return $taxonomy->term_id;
        }, $data['taxonomies'])),
        'posts_per_page' => $data['maxPosts'],
        'ignore_sticky_posts' => 1,
        'post__not_in' => array(get_the_ID())
    ]);

    $data['postTypeArchiveLink'] = get_post_type_archive_link($postType);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingPeople',
        'label' => __('Listing: People', 'flynt'),
        'sub_fields' => [
            // [
            //     'label' => __('General', 'flynt'),
            //     'name' => 'generalTab',
            //     'type' => 'tab',
            //     'placement' => 'top',
            //     'endpoint' => 0
            // ],
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Divisions', 'flynt'),
                'instructions' => __('Select 1 or more divisons or leave empty to show from all people.', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
                'taxonomy' => 'division',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object',
                'wrapper' => [
                    'width' => '50%',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'label' => __('Locations', 'flynt'),
                'instructions' => __('Select 1 or more locations or leave empty to show from all people.', 'flynt'),
                'name' => 'locations',
                'type' => 'taxonomy',
                'taxonomy' => 'location',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object',
                'wrapper' => [
                    'width' => '50%',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'label' => __('Max Number of Items', 'flynt'),
                'instructions' => __('Set to -1 for unlimited', 'flynt'),
                'name' => 'maxPosts',
                'type' => 'number',
                'default_value' => 3,
                'min' => 1,
                'step' => 1,
                'wrapper' => [
                    'width' => '25%',
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
                    'width' => '25%',
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
                    'width' => '25%',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'ASC' => 'Ascending',
                    'DESC' => 'Descending',
                ),
                'default_value' => 'ASC',
                'return_format' => 'value',
                'layout' => 'horizontal',
            ),
            array(
                'label' => 'Show "Read More"',
                'name' => 'show_read_more',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'wrapper' => array(
                    'width' => '25%',
                    'class' => '',
                    'id' => '',
                ),
                'relevanssi_exclude' => 0,
                'message' => '',
                'default_value' => 0,
                'ui' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
        ]
    ];
}

Options::addTranslatable('ListingPeople', [
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
            [
                'label' => __('All Posts', 'flynt'),
                'name' => 'allPosts',
                'type' => 'text',
                'default_value' => __('View all', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('All Posts Link', 'flynt'),
                'name' => 'allPostsLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
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
