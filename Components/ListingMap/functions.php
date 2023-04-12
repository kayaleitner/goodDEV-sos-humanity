<?php

namespace Flynt\Components\ListingMap;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

function getACFLayout()
{
    return [
        'name' => 'ListingMap',
        'label' => __('Interactive Projects Map', 'flynt'),
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
                'label' => __('Project', 'flynt'),
                'name' => 'projects',
                'type' => 'relationship',
                'post_type' => [
                    'project'
                ],
                'filters' => [
                    0 => 'search',
                    1 => 'taxonomy'
                ],
                'allow_null' => 0,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
                'required' => 0,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
            // [
            //     'label' => __('Categories', 'flynt'),
            //     'instructions' => __('Select 1 or more categories or leave empty to show from all posts.', 'flynt'),
            //     'name' => 'taxonomies',
            //     'type' => 'taxonomy',
            //     'taxonomy' => 'category',
            //     'field_type' => 'multi_select',
            //     'allow_null' => 1,
            //     'multiple' => 1,
            //     'add_term' => 0,
            //     'save_terms' => 0,
            //     'load_terms' => 0,
            //     'return_format' => 'object'
            // ],
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
                    FieldVariables\getNavStyle(),
                ]
            ],
        ]
    ];
}

Options::addTranslatable('ListingMap', [
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
