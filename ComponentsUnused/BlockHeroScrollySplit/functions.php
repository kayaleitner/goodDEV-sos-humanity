<?php

namespace Flynt\Components\BlockHeroScrollySplit;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockHeroScrollySplit',
        'label' => __('Scrollytelling: Hero', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Slides', 'flynt'),
                'name' => 'slidesTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Slides',
                'name' => 'slides',
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'layout' => 'table',
                'pagination' => 0,
                'min' => 2,
                'max' => 2,
                'collapsed' => '',
                'button_label' => 'Add Row',
                'sub_fields' => [
                    [
                        'label' => 'Image',
                        'name' => 'image',
                        'aria-label' => '',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 30,
                            'class' => '',
                            'id' => '',
                        ],
                        'return_format' => 'array',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'preview_size' => 'medium',
                        'parent_repeater' => 'field_63f4adae8cef0',
                    ],
                    [
                        'label' => 'Text 1',
                        'name' => 'text_1',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 30,
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_63f4adae8cef0',
                    ],
                    [
                        'label' => 'Text 2',
                        'name' => 'text_2',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 30,
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_63f4adae8cef0',
                    ],
                    [
                        'label' => __('Text Size (mobile)', 'flynt'),
                        'name' => 'textSizeMobile',
                        'type' => 'button_group',
                        'choices' => [
                            'small' => sprintf('<p>30px</p>', __('30px', 'flynt')),
                            'big' => sprintf('<p>40px</p>', __('40px', 'flynt'))
                        ],
                        'wrapper' => [
                            'width' => 10,
                        ],
                        'default_value' => 'big'
                    ],
                ],
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
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                    FieldVariables\getNavStyle('dark-clear'),
                ]
            ]
        ]

    ];
}
