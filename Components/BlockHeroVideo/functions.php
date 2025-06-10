<?php

namespace Flynt\Components\BlockHeroVideo;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'BlockHeroVideo',
        'label' => 'Hero: Video',
        'sub_fields' => [
            [
                'label' => __('Media', 'flynt'),
                'name' => 'tabVideo',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Videos', 'flynt'),
                'type' => 'group',
                'name' => 'videos',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'label' => __('Desktop Video', 'flynt'),
                        'name' => 'videoDesktop',
                        'type' => 'file',
                        'return_format' => 'array',
                        'library' => 'all',
                        'mime_types' => 'mp4,webm',
                        'required' => 1,
                        'instructions' => __('Recommended format: MP4 or WebM. Recommended resolution: 1920x800 or greater.', 'flynt'),
                    ],
                    [
                        'label' => __('Mobile Video', 'flynt'),
                        'name' => 'videoMobile',
                        'type' => 'file',
                        'return_format' => 'array',
                        'library' => 'all',
                        'mime_types' => 'mp4,webm',
                        'required' => 1,
                        'instructions' => __('Recommended format: MP4 or WebM. Recommended resolution: 750x800 or greater.', 'flynt'),
                    ],
                    [
                        'label' => __('Video Caption (Optional)', 'flynt'),
                        'name' => 'caption',
                        'type' => 'text',
                        'instructions' => __('This caption will be shown below the video if set.', 'flynt'),
                    ],
                    [
                        'label' => __('Fallback Image (Optional)', 'flynt'),
                        'name' => 'fallbackImage',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'mime_types' => 'jpg,jpeg,png',
                        'instructions' => __('This image will be shown if the browser does not support video autoplay.', 'flynt'),
                    ]
                ]
            ],
                        [
                'label' => __('Animation', 'flynt'),
                'name' => 'animationTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Show Lottie Animation', 'flynr'),
                'name' => 'showLottieAnimation',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => __('Yes', 'flynt'),
                'ui_off_text' => __('No', 'flynt'),
                'default_value' => 0,
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
                'instructions' => __('The content overlaying the video. Character Recommendations: 5–15.', 'flynt'),
            ],
            [
                'label' => __('Line 2', 'flynt'),
                'name' => 'line2',
                'type' => 'text',
                'instructions' => __('The content overlaying the video. Character Recommendations: 5–15.', 'flynt'),
            ],
        ]
    ];
}

Options::addTranslatable('BlockHeroVideo', [
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