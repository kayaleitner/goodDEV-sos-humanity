<?php

namespace Flynt\Components\BlockStatistics;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;

function getACFLayout()
{
    return [
        'name' => 'blockStatistics',
        'label' => __('Statistics', 'flynt'),
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
                'new_lines' => 'br',
                'type' => 'textarea',
            ],
            [
                'label' => __('Statistics', 'flynt'),
                'name' => 'statisticssTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Statistics', 'flynt'),
                'name' => 'statistics',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Statistic', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Format: JPG, PNG, SVG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png',
                        'wrapper' => [
                            'width' => 25,
                        ],
                    ],
                    [
                        'label' => 'Number',
                        'name' => 'number',
                        'type' => 'group',
                        'layout' => 'table',
                        'wrapper' => [
                            'width' => 25,
                        ],
                        'sub_fields' => [
                            [
                                'label' => __('Prefix', 'flynt'),
                                'name' => 'prefix',
                                'instructions' => 'e.g. $ or €',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => 20
                                ],
                            ],
                            [
                                'label' => __('Amount', 'flynt'),
                                'name' => 'amount',
                                'type' => 'number',
                                'wrapper' => [
                                    'width' => 60
                                ],
                            ],
                            [
                                'label' => __('Unit', 'flynt'),
                                'name' => 'unit',
                                'instructions' => 'e.g. billions or megatons',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => 20
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => __('Description', 'flynt'),
                        'name' => 'description',
                        'instructions' => 'e.g. "premature deaths prevented"',
                        'type' => 'textarea',
                        'new_lines' => 'br',
                        'wrapper' => [
                            'width' => 50,
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
                    FieldVariables\getColorText(),
                    FieldVariables\getColorBackground()
                ]
            ]
        ]
    ];
}
