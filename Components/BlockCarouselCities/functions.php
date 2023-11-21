<?php

namespace Flynt\Components\BlockCarouselCities;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockCarouselCities', function ($data) {
    $data['options'] = gettype($data['options']) === 'array' ? $data['options'] : [];
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});


function getACFLayout()
{
    return [
        'name' => 'blockCarouselCities',
        'label' => __('Carousel: Cities', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Text', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Content',
                'name' => 'content',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'instructions' => 'Displayed as H2',
                        'name' => 'blockTitle',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Paragraph', 'flynt'),
                        'name' => 'textContent',
                        'type' => 'textarea',
                    ],
                    gridCol('colTextStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                    gridCol('colTextSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                ]
            ],
            [
                'label' => __('Cards', 'flynt'),
                'name' => 'cardsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Cards', 'flynt'),
                'name' => 'cards',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add Card', 'flynt'),
                'sub_fields' => [
                    [
                        'name' => 'label',
                        'label' => __('Label', 'flynt'),
                        'type' => 'text',
                    ],
                    [
                        'name' => 'image',
                        'label' => __('Image', 'flynt'),
                        'type' => 'image',
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    [
                        'name' => 'color',
                        'label' => __('Background Color', 'flynt'),
                        'type' => 'color_picker',
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ]
                ]
            ],
            gridCol('colCardsStart', 'Column-Start', ['default_value' => 4], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
            gridCol('colCardsSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
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
                'sub_fields' => []
            ]
        ]
    ];
}
