<?php

namespace Flynt\Components\HeroIllustrationLottie;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'HeroIllustrationLottie',
        'label' => __('Banner: 3 Lottie Illustrations', 'flynt'),
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
                'name' => 'blockTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Illustrations', 'flynt'),
                'name' => 'illustrations',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Illustration', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    // [
                    //     'label' => __('Lottie Animation', 'flynt'),
                    //     'instructions' => __('Upload the lottie animation file here.', 'flynt'),
                    //     'name' => 'lottieAnimationLink',
                    //     'type' => 'url',
                    //     'wrapper' => [
                    //         'width' => 30
                    //     ],
                    // ],
                    [
                        'label' => __('Lottie Animation', 'flynt'),
                        'instructions' => __('Upload the lottie animation file here.', 'flynt'),
                        'name' => 'lottieAnimationLink',
                        'type' => 'file',
                        'return_format' => 'array',
                        'library' => 'all',
                        'mime_types' => 'json',
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Animation Type', 'flynt'),
                        'name' => 'animationType',
                        'type' => 'select',
                        'choices' => [
                            'playOnce' => __('Play Once', 'flynt'),
                            'loop' => __('Loop', 'flynt'),
                            'scrubbed' => __('Scrubbed', 'flynt'),
                        ],
                        'default_value' => 'playOnce',
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ]
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
                    // // FieldVariables\getTheme(),
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                    FieldVariables\getColorSecondary(),
                    FieldVariables\getNavStyle('dark-blur'),
                ]
            ]
        ]
    ];
}
