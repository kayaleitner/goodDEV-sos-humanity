<?php

namespace Flynt\Components\HeroIllustration;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'HeroIllustration',
        'label' => __('Banner: 3 Illustrations', 'flynt'),
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
                'label' => __('Illustrations', 'flynt'),
                'name' => 'illustrations',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Illustration', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    [
                        'label' => __('Illustration', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                        'name' => 'illustration',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'svg',
                        'wrapper' => [
                            'width' => 40
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
                            'width' => 60
                        ],
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
                    // // FieldVariables\getTheme(),
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                    FieldVariables\getColorSecondary(),
                    FieldVariables\getNavStyle('dark-blur'),
                ]
            ]
        ]
    ];
}
