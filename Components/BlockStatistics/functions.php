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
