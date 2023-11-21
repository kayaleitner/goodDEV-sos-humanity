<?php

namespace Flynt\Components\BlockCarouselCities;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;

function getACFLayout()
{
    // die(var_dump(gridCol('colTextStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'wide'])));
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
                    // FieldVariables\getColorText(),
                    // FieldVariables\getColorBackground()
                ]
            ]
        ]
    ];
}
