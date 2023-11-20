<?php

namespace Flynt\Components\BlockHeader;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;
use Timber\Timber;


function getACFLayout()
{
    return [
        'name' => 'BlockHeader',
        'label' => __('Header', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'instructions' => 'Displayed as H1',
                'name' => 'title',
                'type' => 'text',
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Link Button', 'flynt'),
                'name' => 'link',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
            ],
            [
                'label' => __('Images / Videos', 'flynt'),
                'name' => 'imageTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Items', 'flynt'),
                'name' => 'mediaItems',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Media Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Type', 'flynt'),
                        'name' => 'type',
                        'type' => 'button_group',
                        'allow_null' => 0,
                        'default_value' => 'image',
                        'choices' => [
                            'image' => 'Image',
                            'video' => 'Video',
                        ]
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Format: JPG, PNG, SVG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'required' => 1,
                        'mime_types' => 'jpg,jpeg,png',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'image',
                                ],
                            ]
                        ],
                    ],
                    [
                        'label' => __('Video', 'flynt'),
                        'instructions' => __('Format: MP4.', 'flynt'),
                        'name' => 'video',
                        'type' => 'file',
                        'required' => 1,
                        'mime_types' => 'mp4',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'video',
                                ],
                            ]
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg'
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
                    // FieldVariables\getNavStyle('dark-blur'),
                    // FieldVariables\getColorBackground(),
                    gridCol('textWidth', 'Text Width', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                ]
            ]
        ]
    ];
}
