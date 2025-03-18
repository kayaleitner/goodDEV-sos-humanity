<?php

namespace Flynt\Components\SliderVoices;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=SliderVoices', function ($data) {
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'SliderVoices',
        'label' => 'Slider: Quotes',
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
                'name' => 'preContent',
                'type' => 'text',
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Background Image', 'flynt'),
                'name' => 'backgroundImage',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Voices', 'flynt'),
                'name' => 'voicesTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Voices', 'flynt'),
                'name' => 'slides',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Voice', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => [
                            'width' => '20',
                        ],
                    ],
                    [
                        'label' => __('Author', 'flynt'),
                        'name' => 'author',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '20',
                        ],
                    ],
                    [
                        'label' => __('Quote', 'flynt'),
                        'name' => 'quote',
                        'type' => 'textarea',
                        'maxlength' => 120,
                        'wrapper' => [
                            'width' => '60',
                        ],
                    ]
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
                    [
                        'label' => __('Enable Autoplay', 'flynt'),
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1
                    ],
                    [
                        'label' => __('Autoplay Speed (in milliseconds)', 'flynt'),
                        'name' => 'autoplaySpeed',
                        'type' => 'number',
                        'min' => 2000,
                        'step' => 1,
                        'default_value' => 4000,
                        'required' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'autoplay',
                                    'operator' => '==',
                                    'value' => 1
                                ]
                            ]
                        ],
                    ],
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getMaxWidthContainer(),
                    FieldVariables\getPaddingTopBottom(),
                    FieldVariables\getPaddingLeftRight(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                ]
            ]
        ]
    ];
}
