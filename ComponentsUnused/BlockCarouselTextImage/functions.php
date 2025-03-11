<?php

namespace Flynt\Components\BlockCarouselTextImage;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'BlockCarouselTextImage',
        'label' => __('Carousel Text/Image', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'titleTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Block Title', 'flynt'),
                'instructions' => 'Displayed as H2',
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Carousel', 'flynt'),
                'name' => 'carouselTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Text/Image Cards',
                'name' => 'textImageCards',
                'type' => 'repeater',
                'layout' => 'row',
                'sub_fields' => [
                    [
                        'label' => __('Content Title', 'flynt'),
                        'name' => 'contentTitle',
                        'instructions' => __('Max. 20 Characters', 'flynt'),
                        'maxlength' => 20,
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Intro Text', 'flynt'),
                        'name' => 'introText',
                        'instructions' => __('Max. 190 Characters', 'flynt'),
                        'maxlength' => 190,
                        'type' => 'text',
                    ],
                    [
                        'label' => __('CTA Link', 'flynt'),
                        'name' => 'ctaLink',
                        'type' => 'link',
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Format: JPG, JPEG, PNG, SVG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'image',
                                ],
                            ]
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
                    FieldVariables\getCarouselOptions(),
                    FieldVariables\getComponentID(),
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                ],
            ]
        ]
    ];
}
