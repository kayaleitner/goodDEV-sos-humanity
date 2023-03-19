<?php

namespace Flynt\Components\BlockAuthorSelector;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'team';

add_filter('Flynt/addComponentData?name=BlockAuthorSelector', function ($data) {
    $postType = POST_TYPE;

    $data['taxonomies'] = $data['taxonomies'] ?: [];

    $data['posts'] = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $postType,
    ]);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockAuthorSelector',
        'label' => __('Block: Author Selector', 'flynt'),
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
                'label' => __('Internal Author', 'flynt'),
                'instructions' => __('Select 1 or more categories or leave empty to show from all posts.', 'flynt'),
                'name' => 'internalAuthor',
                'type' => 'relationship',
                'post_type' => [
                    'team',
                ],
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object'
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
                    FieldVariables\getTheme(),
                    [
                        'label' => __('Max Columns', 'flynt'),
                        'name' => 'maxColumns',
                        'type' => 'number',
                        'default_value' => 3,
                        'min' => 1,
                        'max' => 4,
                        'step' => 1
                    ]
                ]
            ],
        ]
    ];
}

Options::addTranslatable('BlockAuthorSelector', [
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
