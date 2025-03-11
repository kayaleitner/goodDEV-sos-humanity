<?php

namespace Flynt\Components\BlockListingAuto;

use Flynt\Utils\Options;
use Flynt\FieldVariables;

add_filter('acf/load_field/name=post_types', function ($field) {
    $args = [
        
    ];

    foreach (get_post_types($args, 'objects') as $post_type) {
        // don't list attachments or pages
        if ($post_type->name != 'project' && $post_type->name != 'people' && $post_type->name != 'post' && $post_type->name != 'job') {
            continue;
        }
        $field['choices'][$post_type->name] = $post_type->label;
    }

    // return the field
    return $field;
});

add_filter('acf/load_field/name=flexFilters', function ($field) {
    $args = [
        'public' => true,
    ];

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
    $args = [
        'public' => true,
    ];

    foreach (get_taxonomies($args, 'objects') as $tax) {
        // don't list attachments or pages
        if ($tax->name == 'post_format') {
            continue;
        }
        $field['layouts'][] = [
            'name' => $tax->name,
            'label' => $tax->label,
            'sub_fields' => [
                [
                    'key' => 'flexTax_' . $tax->name,
                    'label' => '',
                    'name' => $tax->name,
                    'aria-label' => '',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'relevanssi_exclude' => 0,
                    'taxonomy' => $tax->name,
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'return_format' => 'id',
                    'field_type' => 'checkbox',
                    'multiple' => 1,
                    'allow_null' => 0,
                ],
            ]
        ];
    }

    // return the field
    return $field;
});

function getACFLayout()
{
    return [
        'name' => 'BlockListingAuto',
        'label' => __('Listing: Auto', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Block Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle_listingAuto',
                'type' => 'text',
            ],
            [
                'label' => __('Title Dark', 'flynt'),
                'name' => 'titleDark',
                'type' => 'textarea',
                "rows" => 2,
                'new_lines' => 'br',
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            [
                'label' => __('Title Green', 'flynt'),
                'instructions' => __('Second sentence to be set in green', 'flynt'),
                'name' => 'titleGreen',
                'type' => 'textarea',
                "rows" => 2,
                'new_lines' => 'br',
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            [
                'label' => __('Intro Text', 'flynt'),
                'name' => 'introText',
                'type' => 'text',   
            ],
            [
                'label' => __('Listing', 'flynt'),
                'name' => 'listingTab',
                'type' => 'tab',
                'placement' => 'top',
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
                'multiple' => 0,
                'allow_null' => 0,
                'ui' => 1,
                'ajax' => 0,
                'placeholder' => '',
            ],
            [
                'label' => 'Taxonomies to display',
                'name' => 'flexTaxonomies',
                'aria-label' => '',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_pageComponents_pageComponents_BlockListingAuto_post_types',
                            'operator' => '<',
                            'value' => '2',
                        ],
                    ],
                    [
                        [
                            'field' => 'field_pageComponents_pageComponents_BlockListingAuto_flexFilters',
                            'operator' => '==empty',
                        ],
                    ],
                ],
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => 'flexTaxonomiesSelector',
                ],
                'relevanssi_exclude' => 0,
                'hide_collapse' => 1,
                'collapse_all_flexible' => 0,
                'btn-icon-only' => 0,
                'min' => '',
                'max' => '',
                'button_label' => 'Add Pre-Filter',
            ],
            [
                'label' => 'User Filters',
                'name' => 'flexFilters',
                'aria-label' => '',
                'type' => 'select',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_pageComponents_pageComponents_BlockListingAuto_post_types',
                            'operator' => '<',
                            'value' => '2',
                        ],
                    ],
                    [
                        [
                            'field' => 'field_pageComponents_pageComponents_BlockListingAuto_flexTaxonomies',
                            'operator' => '==empty',
                        ],
                    ],
                ],
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
            ],
            [
                'label' => __('Max Number of Items', 'flynt'),
                'instructions' => __('Set to -1 for unlimited', 'flynt'),
                'name' => 'maxPosts',
                'type' => 'number',
                'default_value' => 3,
                'min' => -1,
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
                'default_value' => 'date',
                'return_format' => 'value',
                'multiple' => 0,
                'allow_null' => 0,
                'ui' => 0,
                'ajax' => 0,
                'placeholder' => '',
            ],
            [
                'label' => 'Order',
                'name' => 'order',
                'aria-label' => '',
                'type' => 'button_group',
                'instructions' => '',
                'wrapper' => [
                    'width' => '33%',
                    'class' => '',
                    'id' => '',
                ],
                'choices' => [
                    'ASC' => 'Asc.',
                    'DESC' => 'Desc.',
                ],
                'default_value' => 'DESC',
                'return_format' => 'value',
                'layout' => 'horizontal',
            ],
            [
                'label' => 'Show "Load More"',
                'name' => 'show_load_more',
                'aria-label' => '',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '20%',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 0,
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ],
            [
                'label' => __('Load More Text', 'flynt'),
                'name' => 'loadMoreLabel',
                'type' => 'text',
                'default_value' => 'Load More',
                'wrapper' => [
                    'width' => '80%',
                    'class' => '',
                    'id' => '',
                ],
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'show_load_more',
                            'operator' => '==',
                            'value' => 1,
                        ],
                    ]
                ],
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getComponentID(),
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                ],
            ],
        ]
    ];
}

Options::addTranslatable('BlockListingAuto', [
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
                'label' => __('Apply Now', 'flynt'),
                'name' => 'applyNow',
                'type' => 'text',
                'default_value' => __('Apply Now', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Posted On', 'flynt'),
                'name' => 'postedOn',
                'type' => 'text',
                'default_value' => __('Posted On', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
        ],
    ],
    [
        'label' => __('Jobs Fallback', 'flynt'),
        'name' => 'jobsFallbak',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Text', 'flynt'),
        'name' => 'fallBackText',
        'type' => 'wysiwyg',
        'media_upload' => 0,
    ],
    [
        'label' => __('CTA', 'flynt'),
        'instructions' => __('If a form is opened, URL will be ignored', 'flynt'),
        'name' => 'contactCta',
        'type' => 'link',
        'wrapper' => [
            'width' => '33'
        ]
    ],
    [
        'label' => __('Form to open in Popup Modal', 'flynt'),
        'instructions' => __('Select a WPForms form.', 'flynt'),
        'name' => 'contactForm',
        'type' => 'post_object',
        'post_type' => ['wpforms'],
        'allow_null' => 1,
        'wrapper' => [
            'width' => '33'
        ]
    ],
    [
        'label' => __('LinkedIn', 'flynt'),
        'name' => 'linkedin',
        'type' => 'url',
        'wrapper' => [
            'width' => '33'
        ]
    ],
]);