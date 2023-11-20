<?php

namespace Flynt\Components\BlockImageText;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;

function getACFLayout()
{
    return [
        'name' => 'blockImageText',
        'label' => __('Image/Text', 'flynt'),
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
                'instructions' => 'Displayed as H2',
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Media Position', 'flynt'),
                'name' => 'mediaPosition',
                'type' => 'button_group',
                'choices' => [
                    'left' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Image on the left', 'flynt')),
                    'right' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Image on the right', 'flynt'))
                ],
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => 'Media',
                'name' => 'media',
                'type' => 'group',
                'layout' => 'row',
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
                    gridCol('colMediaStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                    gridCol('colMediaSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                ]
            ],

            [
                'label' => 'Content',
                'name' => 'content',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'delay' => 1,
                        'media_upload' => 0,
                        'required' => 0,
                    ],
                    gridCol('colTextStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                    gridCol('colTextSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
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
                    FieldVariables\getColorText(),
                    FieldVariables\getColorBackground()
                ]
            ]
        ]
    ];
}
