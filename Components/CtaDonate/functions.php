<?php

namespace Flynt\Components\CtaDonate;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

// add_filter('Flynt/addComponentData?name=CtaDonate', function ($data) {
//     $data['amounts'] = [25, 50, 75, 100];
//     return $data;
// });

function getACFLayout()
{
    return [
        'name' => 'CtaDonate',
        'label' => 'CTA: Donate',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Side Title', 'flynt'),
                'name' => 'sideTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual,text',
                'media_upload' => 0,
                'delay' => 1,
            ],
            [
                'label' => __('Fixed Donations', 'flynt'),
                'name' => 'fixedDonations',
                'type' => 'group',
                'tabs' => 'visual,text',
                'media_upload' => 0,
                'delay' => 1,
                'sub_fields' => [
                    [
                        'label' => __('Amount', 'flynt'),
                        'name' => 'amount1',
                        'type' => 'number',
                        'required' => 1,
                        'wrapper' =>  [
                            'width' => '25',
                        ],
                    ],
                    [
                        'label' => __('Amount', 'flynt'),
                        'name' => 'amount2',
                        'type' => 'number',
                        'required' => 1,
                        'wrapper' =>  [
                            'width' => '25',
                        ],
                    ],
                    [
                        'label' => __('Amount', 'flynt'),
                        'name' => 'amount3',
                        'type' => 'number',
                        'required' => 1,
                        'wrapper' =>  [
                            'width' => '25',
                        ],
                    ],
                    [
                        'label' => __('Amount', 'flynt'),
                        'name' => 'amount4',
                        'type' => 'number',
                        'required' => 1,
                        'wrapper' =>  [
                            'width' => '25',
                        ],
                    ],
                ]
            ],
            [
                'label' => __('Donation Page', 'flynt'),
                'name' => 'ctaLink',
                'type' => 'link',
                'instructions' => 'The link to the donation page.',
                'required' => 1,
                'default_value' => [
                    'url' => '/spenden/',
                ],
                'return_format' => 'array'
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
                    FieldVariables\getMaxWidthContainer(),
                ]
            ]
        ]
    ];
}

Options::addTranslatable('CtaDonate', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Placeholder Text', 'flynt'),
        'name' => 'placeholderText',
        'type' => 'text',
        'wrapper' =>  [
            'width' => 50,
        ],
    ],
    [
        'label' => __('Button Text', 'flynt'),
        'name' => 'buttonText',
        'type' => 'text',
        'wrapper' =>  [
            'width' => 50,
        ],
    ],
]);
