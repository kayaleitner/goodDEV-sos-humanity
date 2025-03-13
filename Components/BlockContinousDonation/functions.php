<?php

namespace Flynt\Components\BlockContinousDonation;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'BlockContinousDonation',
        'label' => 'Continous Donation', 
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Current amount of continuous donations', 'flynt'), 
                'name' => 'currentAmount',
                'type' => 'number',
                'wrapper' => [
                    'width' => '33',
                ],
            ],
            [
                'label' => __('Current position offset', 'flynt'),
                'name' => 'currentPositionOffset',
                'instructions' => __('This is the offset of the current position in px (half on mobile - because donation items are half as wide). This is used to adjust the position of the hands.', 'flynt'),
                'type' => 'number',
                'default_value' => 0,
                'wrapper' => [
                    'width' => '33',
                ],
                'min' => -200,
                'max' => 200,
            ],
            [
                'label' => __('Donation items', 'flynt'),
                'name' => 'donationItems',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add donation item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Item', 'flynt'),
                        'name' => 'donationItem',
                        'type' => 'group',
                        'layout' => 'block',
                        'sub_fields' => [
                            [
                                'label' => __('Title', 'flynt'),
                                'name' => 'title',
                                'type' => 'text',
                                'required' => 1,
                                'wrapper' => [
                                    'width' => '33',
                                ],
                            ],
                            [
                                'label' => __('Amount', 'flynt'),
                                'name' => 'amount',
                                'type' => 'number',
                                'required' => 1,
                                'wrapper' => [
                                    'width' => '33',
                                ],
                            ],
                            [
                                'label' => __('Spacing to previous item', 'flynt'),
                                'name' => 'spacing',
                                'type' => 'select',
                                'choices' => [
                                    'spacing-small' => __('Small', 'flynt'),
                                    'spacing-medium' => __('Medium', 'flynt'),
                                    'spacing-large' => __('Large', 'flynt'),
                                ],
                                'wrapper' => [
                                    'width' => '33',
                                ],
                            ],
                            [
                                'label' => __('Icon', 'flynt'),
                                'name' => 'icon',
                                'type' => 'image',
                                'preview_size' => 'medium',
                                'wrapper' => [
                                    'width' => '50',
                                ],
                            ],
                            [
                                'label' => __('Link', 'flynt'),
                                'name' => 'link',
                                'type' => 'link',
                                'return_format' => 'array',
                                'wrapper' => [
                                    'width' => '50',
                                ],
                            ],
                            [
                                'label' => __('Goal reached', 'flynt'),
                                'name' => 'goalReached',
                                'type' => 'true_false',
                                'instructions' => __('Select this if the goal for this item has been reached!', 'flynt'),
                                'wrapper' => [
                                    'width' => '33',
                                ],
                            ],
                            [
                                'label' => __('Current position', 'flynt'),
                                'name' => 'currentPosition',
                                'instructions' => __('Select this if this is the element where the hands should be! Please make sure only one element has this box checked!', 'flynt'),
                                'type' => 'true_false',
                                'wrapper' => [
                                    'width' => '33',
                                ],
                            ],
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
                    FieldVariables\getPaddingTopBottom('var(--padding_none)'),
                ]
            ]
        ],
    ]; 
}


Options::addTranslatable('BlockContinousDonation', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Gesamtsumme Label', 'flynt'),
        'name' => 'sumLabel',
        'type' => 'text',
        'wrapper' =>  [
            'width' => 50,
        ],
    ],
    [
        'label' => __('CTA Button', 'flynt'),
        'name' => 'cta',
        'type' => 'link',
        'wrapper' =>  [
            'width' => 50,
        ],
    ],
    [
        'label' => __('Spendenposition Link Label', 'flynt'),
        'name' => 'linkLabel',
        'type' => 'text',
        'wrapper' =>  [
            'width' => 50,
        ],
    ],
]);