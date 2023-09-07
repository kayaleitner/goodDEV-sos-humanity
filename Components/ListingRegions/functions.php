<?php

namespace Flynt\Components\ListingRegions;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=ListingRegions', function ($data) {
    $globalTheme = get_field('field_optionsMeta_globalThemeOption', 'options' );
    $data['globalTheme'] = $globalTheme;
    return $data;
});


function getACFLayout()
{
    return [
        'name' => 'ListingRegions',
        'label' => __('Interactive Regions Map', 'flynt'),
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
                'label' => __('Regions', 'flynt'),
                'name' => 'regions',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Name', 'flynt'),
                        'name' => 'name',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => 30
                        ],
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png',
                        'wrapper' => [
                            'width' => 30
                        ],
                    ],
                    [
                        'label' => __('Short Description', 'flynt'),
                        'name' => 'excerpt',
                        'type' => 'textarea',
                        'wrapper' => [
                            'width' => 30
                        ],
                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'link',
                        'type' => 'link',
                        'wrapper' => [
                            'width' => 30
                        ],
                    ],
                    [
                        'label' => __('Countries to Highlight', 'flynt'),
                        'instructions' => __('List the countries to highlight with their 3-letter ISO-Codes as JSON-Array', 'flynt'),
                        'name' => 'countries',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => 70
                        ],
                    ],
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
                    FieldVariables\getNavStyle(),
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                ]
            ],
        ]
    ];
}

Options::addTranslatable('ListingRegions', [
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
                'label' => 'Region Tabs Title',
                'name' => 'regionTabsTitle',
                'type' => 'text',
                'default_value' => __('Select Region:', 'flynt'),
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => 'Region Label on Regions Card',
                'name' => 'regionsLabel',
                'type' => 'text',
                'default_value' => __('Region', 'flynt'),
                'wrapper' => [
                    'width' => 50
                ],
            ],
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
