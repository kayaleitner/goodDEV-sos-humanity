<?php

namespace Flynt\Components\BlockScrollySlides;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockScrollySlides', function ($data) {
    $data['options'] = gettype($data['options']) === 'array' ? $data['options'] : [];
    $translatableOptions = Options::getTranslatable('BlockScrollySlidesOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});


function getACFLayout()
{
    return [
        'name' => 'blockScrollySlides',
        'label' => __('Scrolly Slides', 'flynt'),
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
                'instructions' => 'Displayed as H2',
                'name' => 'blockTitle',
                'type' => 'text',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'name' => 'topImage',
                'label' => __('Top Right Image', 'flynt'),
                'type' => 'image',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Slides', 'flynt'),
                'name' => 'slidesTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Slides', 'flynt'),
                'name' => 'slides',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add Slide', 'flynt'),
                'sub_fields' => [
                    [
                        'name' => 'image',
                        'label' => __('Image', 'flynt'),
                        'type' => 'image',
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    [
                        'name' => 'text',
                        'label' => __('Text', 'flynt'),
                        'type' => 'wysiwyg',
                    ],
                    [
                        'name' => 'bgColor',
                        'label' => __('Background Color', 'flynt'),
                        'default_value' => '#ffffff',
                        'type' => 'color_picker',
                        'required' => 1,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    [
                        'name' => 'color',
                        'label' => __('Text Color', 'flynt'),
                        'default_value' => '#003574',
                        'type' => 'color_picker',
                        'required' => 1,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    [
                        'name' => 'ctaLink',
                        'label' => __('CTA Link', 'flynt'),
                        'type' => 'link',
                    ],
                ],
                // min 1 max 3
                'min' => 1,
                'max' => 3,
            ],
            // gridCol('colCardsStart', 'Column-Start', ['default_value' => 4], [], [], [], ['mobile', 'tablet', 'desktop', 'max']),
            // gridCol('colCardsSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'max']),
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
                    FieldVariables\getColorText()
                ],
            ]
        ]
    ];
}
