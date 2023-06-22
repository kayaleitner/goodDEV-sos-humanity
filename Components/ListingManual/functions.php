<?php

namespace Flynt\Components\ListingManual;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

// add_filter('Flynt/addComponentData?name=ListingManual', function ($data) {

//     return $data;
// });

function getACFLayout()
{
    return [
        'name' => 'ListingManual',
        'label' => __('Listing: Manual', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => 'Items to display',
                'name' => 'items',
                'aria-label' => '',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 0,
                'post_type' => [
                    0 => 'post',
                    1 => 'people',
                    2 => 'project',
                ],
                'post_status' => '',
                'taxonomy' => '',
                'filters' => [
                    0 => 'search',
                    1 => 'post_type',
                    2 => 'taxonomy',
                ],
                'return_format' => 'object',
                'min' => '',
                'max' => '',
                'elements' => '',
            ],
            [
                'label' => '"View All"-Link',
                'name' => 'viewAllLink',
                'aria-label' => '',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 0,
                'message' => '',
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ],
        ]
    ];
}

Options::addTranslatable('ListingManual', [
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
