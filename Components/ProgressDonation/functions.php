<?php

namespace Flynt\Components\ProgressDonation;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'progressDonation',
        'label' => 'Progress: Donation',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Spendenziel', 'flynt'),
                'name' => 'donationGoal',
                'type' => 'number',
                'instructions' => __('Geben Sie hier das Spendenziel ein.', 'flynt'),
            ],
            [
                'label' => __('Spendenstand', 'flynt'),
                'name' => 'donationLevel',
                'type' => 'number',
                'instructions' => __('Geben Sie hier den aktuellen Spendenstand ein.', 'flynt'),
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
                    FieldVariables\getPaddingTopBottom(),
                    FieldVariables\getPaddingLeftRight(),
                ]
            ]
        ]
    ];
}