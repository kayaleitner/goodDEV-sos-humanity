<?php

namespace Flynt\Components\BlockTicker;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockTicker',
        'label' => 'Block: Ticker',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Side Title', 'flynt'),
                'name' => 'sideTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Images', 'flynt'),
                'type' => 'group',
                'name' => 'images',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'label' => __('Desktop Image', 'flynt'),
                        'name' => 'imageDesktop',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'mime_types' => 'jpg,jpeg,png',
                        'required' => 1,
                        'instructions' => __('Image-Format: JPG, PNG. Recommended resolution greater than 2048 x 800 px.', 'flynt'),
                    ],
                    [
                        'label' => __('Mobile Image', 'flynt'),
                        'name' => 'imageMobile',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'mime_types' => 'jpg,jpeg,png',
                        'required' => 1,
                        'instructions' => __('Image-Format: JPG, PNG. Recommended resolution greater than 750 x 800 px.', 'flynt'),
                    ]
                ]
            ],
            [
                'label' => __('Left Tab', 'flynt'),
                'name' => 'leftTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContentLeft',
                'type' => 'text',
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'leftContent',
                'type' => 'textarea'
            ],
            [
                'label' => __('CTA Link', 'flynt'),
                'name' => 'ctaLink',
                'type' => 'link',
                'return_format' => 'array'
            ],
            [
                'label' => __('Right Tab', 'flynt'),
                'name' => 'rightTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContentRight',
                'type' => 'text',
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Subtitle', 'flynt'),
                'name' => 'preContentSubtitleRight',
                'type' => 'text',
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Ticker Panels', 'flynt'),
                'name' => 'tickerPanels',
                'type' => 'repeater',
                'layout' => 'table',
                'min' => 1,
                'button_label' => __('Add Ticker News', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'panelTitle',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '20',
                        ],
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'panelContent',
                        'type' => 'textarea',
                        'wrapper' => [
                            'width' => '80',
                        ],
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
                'layout' => 'block',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                ]
            ]
        ]
    ];
}
