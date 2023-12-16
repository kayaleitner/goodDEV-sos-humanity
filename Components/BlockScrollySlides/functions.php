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
            // [
            //     'label' => 'Content',
            //     'name' => 'content',
            //     'type' => 'group',
            //     'layout' => 'block',
            //     'sub_fields' => [
            //         [
            //             'label' => __('Title', 'flynt'),
            //             'instructions' => 'Displayed as H2',
            //             'name' => 'blockTitle',
            //             'type' => 'text',
            //         ],
            //         [
            //             'label' => __('Paragraph', 'flynt'),
            //             'name' => 'textContent',
            //             'type' => 'textarea',
            //         ],
            //         [
            //             'label' => __('CTA Link', 'flynt'),
            //             'name' => 'ctaLink',
            //             'type' => 'link',
            //         ],
            //         gridCol('colTextStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
            //         gridCol('colTextSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
            //     ]
            // ],
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
            // gridCol('colCardsStart', 'Column-Start', ['default_value' => 4], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
            // gridCol('colCardsSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
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
