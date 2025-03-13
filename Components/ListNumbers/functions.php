<?php

namespace Flynt\Components\ListNumbers;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'ListNumbers',
        'label' => 'List Facts',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Numbers', 'flynt'),
                'name' => 'panelNumbers',
                'type' => 'repeater',
                'layout' => 'table',
                'min' => 1,
                'button_label' => __('Add Number', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'numberTitle',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'numberContent',
                        'type' => 'text'
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
                    // FieldVariables\getMaxWidthContainer(),
                    // FieldVariables\getPaddingTopBottom(),
                    // FieldVariables\getPaddingLeftRight(),
                ]
            ]
        ],
    ];
}
