<?php

namespace Flynt\Components\BlockOembed;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Flynt\Utils\Oembed;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockOembed', function ($data) {
    $data['oembed'] = Oembed::setSrcAsDataAttribute(
        $data['oembed'],
        ['autoplay' => 'true']
    );

    return $data;
});

// WIP Solution (autoplay not working)
// add_filter('Flynt/addComponentData?name=BlockOembed', function ($data) {
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
        'name' => 'blockOembed',
        'label' =>  __('oEmbed (Video/Visualisation)', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Text', 'flynt'),
                'name' => 'textTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
            ],
            [
                'label' => __('Media', 'flynt'),
                'name' => 'mediaTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Video Mode', 'flynt'),
                'instructions' => __('Places a play button and requires a poster image.', 'flynt'),
                'name' => 'videoMode',
                'type' => 'button_group',
                'allow_null' => 0,
                'default_value' => 'off',
                'choices' => [
                    'on' => 'On',
                    'off' => 'Off',
                ],
                'wrapper' => [
                    'width' => 35,
                ],
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
                    'width' => 35,
                ],
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'videoMode',
                            'operator' => '==',
                            'value' => 'on',
                        ],
                    ]
                ],
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

Options::addTranslatable('BlockOembed', [
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
