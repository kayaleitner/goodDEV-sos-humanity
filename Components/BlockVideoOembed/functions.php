<?php

namespace Flynt\Components\BlockVideoOembed;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Flynt\Utils\Oembed;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockVideoOembed', function (array $data): array {
    $data['oembed'] = Oembed::setSrcAsDataAttribute(
        $data['oembed'],
        ['autoplay' => 1, 'enablejsapi' => 1],
    );

    return $data;
});

// WIP Solution (autoplay not working)
// add_filter('Flynt/addComponentData?name=BlockVideoOembed', function ($data) {
//     $oembed = Oembed::setSrcAsDataAttribute(
//         $oembed,
//         ['autoplay' => 'true']
//     );

//     if (in_array('wp-content/plugins/borlabs-cookie/borlabs-cookie.php', apply_filters('active_plugins', get_option('active_plugins')))) {
//         $oembed = '[borlabs-cookie id="youtube" type="content-blocker"]' . $oembed . '[/borlabs-cookie]';
//     } else {
//         $oembed = $data['oembed'];
//     }

//     return $data;
// });

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
                'instructions' => __('Image-Format: JPG, PNG, SVG, WebP. Aspect Ratio: 16:9. Recommended Size: 1920px × 1080px.', 'flynt'),
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
