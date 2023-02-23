<?php

namespace Flynt\Components\BlockVideoOembed;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'blockVideoOembed',
        'label' =>  __('Block: Video Oembed', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            // [
            //     'label' => __('Poster Image', 'flynt'),
            //     'instructions' => __('Recommended Size: Min-Width 1920px; Min-Height: 1080px; Image-Format: JPG, PNG. Aspect Ratio 16/9.', 'flynt'),
            //     'name' => 'posterImage',
            //     'type' => 'image',
            //     'preview_size' => 'medium',
            //     'mime_types' => 'jpg,jpeg,png',
            //     'required' => 1
            // ],
            [
                'label' => __('Video', 'flynt'),
                'name' => 'oembed',
                'type' => 'oembed',
                'required' => 1,
                'videoParams' => [
                    'autoplay' => 1,
                ]
            ]
        ]
    ];
}
