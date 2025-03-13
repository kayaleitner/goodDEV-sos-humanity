<?php

namespace Flynt\Components\DonationSlider;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

// add_filter('Flynt/addComponentData?name=DonationSlider', function ($data) {
//     $translatableOptions = Options::getTranslatable('CtaDonate', 'placeholderText');
//     $data['translatableOptions'] = $translatableOptions;
//     return $data;
// });


function getACFLayout()
{
    return [
        'name' => 'DonationSlider',
        'label' => 'Donation Slider',
        'sub_fields' => [
            [
                'label' => __('Progress', 'flynt'),
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
                'label' => __('Images', 'flynt'),
                'name' => 'accordionImages',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
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
                        'required' => 0,
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
                        'required' => 0,
                        'instructions' => __('Image-Format: JPG, PNG. Recommended resolution greater than 750 x 800 px.', 'flynt'),
                    ]
                ]
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'accordionContent',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'contentTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Text in box', 'flynt'),
                'name' => 'contentText',
                'type' => 'wysiwyg',
                'tabs' => 'visual,text',
                'media_upload' => 0,
                'delay' => 1,
                'instructions' => __('The content in the yellow box. Character Recommendations: Title: 30-100, Content: 80-250.', 'flynt'),
            ],
            [
                'label' => __('Donation', 'flynt'),
                'name' => 'donationTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
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
        ]
    ];
}


Options::addTranslatable('DonationSlider', [
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