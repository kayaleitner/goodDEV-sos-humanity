<?php

namespace Flynt\Components\BlockVideoOembed;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Flynt\Utils\Oembed;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockVideoOembed', function ($data) {
    $data['video'] = Oembed::setSrcAsDataAttribute(
        $data['oembed'],
        [
            'autoplay' => 'true'
        ]
    );
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockVideoOembed',
        'label' =>  __('Video', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Imagery', 'flynt'),
                'name' => 'imageryTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Poster Image', 'flynt'),
                'instructions' => __('Recommended Size: Min-Width 1920px; Min-Height: 1080px; Image-Format: JPG, PNG. Aspect Ratio 16/9.', 'flynt'),
                'name' => 'posterImage',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png',
                'required' => 1,
                'wrapper' => [
                    'width' => 30,
                ]
            ],
            [
                'label' => __('Video', 'flynt'),
                'name' => 'oembed',
                'type' => 'oembed',
                'required' => 0,
                'videoParams' => [
                    'autoplay' => 1,
                ],
                'wrapper' => [
                    'width' => 70,
                ]
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'textarea',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
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
                    // FieldVariables\getTheme(),
                    FieldVariables\getNavStyle(),
                ]
            ]
        ]
    ];
}

Options::addTranslatable('BlockVideoOembed', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('Play Button Text', 'flynt'),
                'name' => 'playButtonText',
                'type' => 'text',
                'default_value' => __('Play Video', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ],
        ],
    ]
]);
