<?php

namespace Flynt\Components\BlockImageTextTitle;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockImageTextTitle',
        'label' => 'Block: Humanity Frame (Home)',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Image', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'instructions' => __('Image-Format: JPG, PNG. Minimum width of the image should be 792px (recommended width: 1584px); the aspect ratio should be 3:2', 'flynt'),
                'min_width' => 792,
                'required' => 1,
                'mime_types' => 'jpg,jpeg,png',
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
                'wrapper' => [
                    'width' => '70',
                ],
            ],
            [
                'label' => __('Content Background', 'flynt'),
                'name' => 'contentBackgroundColor',
                'type' => 'color_picker',
                'required' => 0,
                'wrapper' => [
                    'width' => '30',
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
                    
                ]
            ]
        ]
    ];
}
