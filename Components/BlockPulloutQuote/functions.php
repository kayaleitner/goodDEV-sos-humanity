<?php

namespace Flynt\Components\BlockPulloutQuote;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockPulloutQuote',
        'label' => __('Pullout Quote', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Quotes', 'flynt'),
                'name' => 'quotes',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Quote', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    [
                        'label' => __('Border Top', 'flynt'),
                        'instructions' => __('Add top border', 'flynt'),
                        'name' => 'borderTop',
                        'type' => 'true_false',
                        'ui' => 1,
                        'ui_on_text' => 'Yes',
                        'ui_off_text' => 'No',
                        'default_value' => 1,
                        'wrapper' => [
                            'width' => 100,
                        ]
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                        'name' => 'blockTitle',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => [
                            'width' => 40
                        ],
                    ],
                    [
                        'label' => __('Image Caption', 'flynt'),
                        'name' => 'imageCaptionHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => 60
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
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                    FieldVariables\getColorSecondary(),
                    FieldVariables\getNavStyle(),
                ]
            ]
        ]
    ];
}
