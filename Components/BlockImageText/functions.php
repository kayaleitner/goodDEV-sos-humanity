<?php

namespace Flynt\Components\BlockImageText;

use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;

function getACFLayout(): array
{
    return [
        'name' => 'blockImageText',
        'label' => __('Image/Text', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Image', 'flynt'),
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
                'label' => __('Media Position (mobile)', 'flynt'),
                'name' => 'mediaPositionMobile',
                'type' => 'button_group',
                'choices' => [
                    'top' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Image on the left', 'flynt')),
                    'bottom' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Image on the right', 'flynt'))
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
                        'instructions' => __('Provide a poster image and various formats for best performance', 'flynt'),
                        'name' => 'video',
                        'type' => 'group',
                        'layout' => 'row',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'video',
                                ],
                            ]
                        ],
                        'sub_fields' => [
                            [
                                'label' => __('Aria-Label', 'flynt'),
                                'name' => 'label',
                                'type' => 'text',
                                'required' => 1,
                            ],
                            [
                                'label' => __('Poster image', 'flynt'),
                                'instructions' => __('Image-Format: JPG, PNG, WEBM (choose smallest size!)', 'flynt'),
                                'name' => 'posterImage',
                                'type' => 'image',
                                'preview_size' => 'medium',
                                'required' => 0,
                                'mime_types' => 'jpg,jpeg,png,webp'
                            ],
                            [
                                'label' => __('Items', 'flynt'),
                                'name' => 'videoFiles',
                                'type' => 'repeater',
                                'layout' => 'table',
                                'min' => 1,
                                'button_label' => __('Add video format', 'flynt'),
                                'instructions' => __('Provide video in h264 (mp4), h265 (mp4), vp8 (webm) and vp9 (webm). Sort ascending by size!', 'flynt'),
                                'sub_fields' => [
                                    [
                                        'label' => __('Video', 'flynt'),
                                        'name' => 'videoFile',
                                        'type' => 'file',
                                        'required' => 1,
                                        'mime_types' => 'mp4,webm',
                                    ],
                                    [
                                        'label' => __('Codec', 'flynt'),
                                        'name' => 'codec',
                                        'type' => 'select',
                                        'required' => 1,
                                        'choices' => [
                                            'avc1' => 'h264',
                                            'hvc1' =>'h265',
                                            'vp8' =>'vp8',
                                            'vp9' => 'vp9',
                                        ],
                                        'default_value' => 'avc1',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    gridCol('colMediaStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'max']),
                    gridCol('colMediaSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'max']),
                ]
            ],
            [
                'label' => __('Text', 'flynt'),
                'name' => 'textTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
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
                    gridCol('colTextStart', 'Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'max']),
                    gridCol('colTextSpan', 'Column-Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'max']),
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
