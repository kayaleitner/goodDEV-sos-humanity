<?php

namespace Flynt\Components\ListingProjects;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'project';

add_filter('Flynt/addComponentData?name=ListingProjects', function ($data) {
    $postType = POST_TYPE;

    $customerSegments = $data['customerSegments'] ?: [];
    $country = $data['country'] ?: [];

    if (empty($customerSegments) && empty($country)) {
        $data['posts'] = Timber::get_posts([
            'post_status' => 'publish',
            'post_type' => $postType,
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => 4,
            'ignore_sticky_posts' => 1,
            'post__not_in' => array(get_the_ID()),
        ]);
    } else {
        $data['posts'] = Timber::get_posts([
            'post_status' => 'publish',
            'post_type' => $postType,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'customer_segment',
                    'field' => 'slug',
                    'terms' => $customerSegments,
                ),
                array(
                    'taxonomy' => 'country',
                    'field' => 'slug',
                    'terms' => $country,
                )
            ),
            'posts_per_page' => $data['options']['maxPosts'],
            'ignore_sticky_posts' => 1,
            'post__not_in' => array(get_the_ID())
        ]);
    }

    return $data;
});

// ajax
add_action('wp_ajax_get_posts', 'Flynt\Components\ListingProjects\get_posts');
add_action('wp_ajax_nopriv_get_posts', 'Flynt\Components\ListingProjects\get_posts');

function get_posts()
{

    $context = Timber::get_context();

    $customerSegments = $data['customerSegments'] ?: [];
    $country = $data['country'] ?: [];

    if (empty($customerSegments) && empty($country)) {
        $data['items'] = Timber::get_posts([
            'post_status' => 'publish',
            'post_type' => 'project',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $_POST['count'],
            'offset' => $_POST['offset'],
            'post__not_in' => array(get_the_ID()),
        ]);
    } else {
        $data['posts'] = Timber::get_posts([
            'post_status' => 'publish',
            'post_type' => 'project',
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'customer_segment',
                    'field' => 'slug',
                    'terms' => $customerSegments,
                ),
                array(
                    'taxonomy' => 'country',
                    'field' => 'slug',
                    'terms' => $country,
                )
            ),
            'posts_per_page' => $_POST['count'],
            'offset' => $_POST['offset'],
            'post__not_in' => array(get_the_ID())
        ]);
    }

    foreach ($context['posts'] as $post) {
        Timber::render('/Partials/_post.twig', array('post' => $post));
    }

    wp_die();
}

function getACFLayout()
{
    return [
        'name' => 'ListingProjects',
        'label' => __('Listing: Projects', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Customer Segment', 'flynt'),
                'instructions' => __('Select 1 or more categories or leave empty to show from all posts.', 'flynt'),
                'name' => 'customerSegments',
                'type' => 'taxonomy',
                'taxonomy' => 'customer_segment',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object',
                'wrapper' => [
                    'width' => 50
                ]
            ],
            [
                'label' => __('Country', 'flynt'),
                'instructions' => __('Select 1 or more categories or leave empty to show from all posts.', 'flynt'),
                'name' => 'country',
                'type' => 'taxonomy',
                'taxonomy' => 'country',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object',
                'wrapper' => [
                    'width' => 50
                ]
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    // FieldVariables\getTheme(),
                    [
                        'label' => __('Max Posts', 'flynt'),
                        'name' => 'maxPosts',
                        'type' => 'number',
                        'default_value' => 3,
                        'min' => -1,
                        'max' => 16,
                        'step' => 1
                    ]
                ]
            ],
        ]
    ];
}

Options::addTranslatable('ListingProjects', [
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
