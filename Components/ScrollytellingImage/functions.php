<?php

namespace Flynt\Components\ScrollytellingImage;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'ScrollytellingImage',
        'label' => __('Scrollytelling: Images', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Scrollytelling', 'flynt'),
                'name' => 'scrollytelling',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Item', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => [
                            'width' => 30
                        ],
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => 50
                        ],
                    ],
                    [
                        'label' => __('Hide on Mobile', 'flynt'),
                        'instructions' => __('If "Yes" is selected, this element will be hidden on mobile.', 'flynt'),
                        'name' => 'hideOnMobile',
                        'type' => 'true_false',
                        'ui' => 1,
                        'ui_on_text' => 'Yes',
                        'ui_off_text' => 'No',
                        'default_value' => 0,
                        'wrapper' => [
                            'width' => 20,
                        ]
                    ]
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
                    FieldVariables\getNavStyle('dark-blur'),
                ]
            ]
        ]
    ];
}
