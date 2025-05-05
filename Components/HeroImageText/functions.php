<?php

namespace Flynt\Components\HeroImageText;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'heroImageText',
        'label' => 'Hero: Header Headline',
        'sub_fields' => [
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
                'label' => __('Content', 'flynt'),
                'name' => 'accordionContent',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Line 1', 'flynt'),
                'name' => 'line1',
                'type' => 'text',
                'tabs' => 'visual,text',
                'instructions' => __('The content overlaying the image. Character Recommendations: 5-15.', 'flynt'),
            ],
            [
                'label' => __('Line 2', 'flynt'),
                'name' => 'line2',
                'type' => 'text',
                'tabs' => 'visual,text',
                'instructions' => __('The content overlaying the image. Character Recommendations: 5-15', 'flynt'),
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
                    // FieldVariables\getMaxWidthContainer(),
                    FieldVariables\getPaddingTopBottom(),
                    FieldVariables\getPaddingLeftRight(),
                ]
            ]
        ]
    ];
}   


Options::addTranslatable('HeroImageText', [
    [
        'label' => __('Animations'),
        'name' => 'AnimationTab',
        'type' => 'tab',
    ],
    [
        'label' => __('Lottie', 'flynt'),
        'instructions' => __('Provide a lottie file', 'flynt'),
        'name' => 'lottie',
        'type' => 'group',
        'layout' => 'row',
        'sub_fields' => [
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
                'label' => __('Label', 'flynt'),
                'instructions' => __('Provide an aria-label for accessibility.', 'flynt'),
                'name' => 'ariaLabel',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Page Link', 'flynt'),
                'instructions' => __('Select a page to link to.', 'flynt'),
                'name' => 'pageLink',
                'type' => 'link',
                'post_type' => [],
                'allow_null' => 1,
                'multiple' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
        ],
    ],
]);