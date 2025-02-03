<?php

namespace Flynt\Components\BlockCarouselLogo;

use Flynt\FieldVariables;

function getACFLayout(): array 
{
    return [
        'name' => 'BlockCarouselLogo',
        'label' => __('Carousel Logo', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => '',
                'name' => 'general',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    [
                        'label' => __('Block Title', 'flynt'),
                        'name' => 'blockTitle',
                        'type' => 'text',
                    ]
                ]
            ],
            [
                'label' => __('Carousel', 'flynt'),
                'name' => 'carouselTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'CTA Button',
                'name' => 'ctaButton',
                'type' => 'link',
            ],
            [
                'label' => '',
                'name' => 'logos',
                'type' => 'repeater',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'label' => __('Logo', 'flynt'),
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'mime_types' => 'webp,jpg,jpeg,png,svg',
                    ],
                ],
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getCarouselOptions(),
                    FieldVariables\getComponentID(),
                    FieldVariables\getColorBackground('var(--brandColor)'),
                    FieldVariables\getColorText('var(--paperColor)'),
                ],
            ],
        ],
    ];
}
