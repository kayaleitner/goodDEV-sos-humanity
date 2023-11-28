<?php

namespace Flynt\Components\BlockHeader;

use Flynt\Utils\Asset;
use Flynt\ComponentManager;
use Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;
use Timber\Timber;


function getACFLayout()
{
    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('BlockHeader');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);
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
                'max' => 3,
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
                'layout' => 'row',
                'sub_fields' => [
                    // FieldVariables\getTheme(),
                    // FieldVariables\getNavStyle('dark-blur'),
                    FieldVariables\getColorBackground(),
                    gridCol('textWidth', 'Text Columns', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                    gridCol('colMediaStart', 'Media Column-Start', ['default_value' => 1], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                    gridCol('colMediaSpan', 'Media Column Span', [], [], [], [], ['mobile', 'tablet', 'desktop', 'wide']),
                    [
                        'label' => __('Indent intro text', 'flynt'),
                        'name' => 'textIndent',
                        'type' => 'true_false',
                        'ui' => 1,
                        'ui_on_text' => __('Yes', 'flynt'),
                        'ui_off_text' => __('No', 'flynt'),
                        'default_value' => 1,
                    ],
                    [
                        'label' => __('Media layout', 'flynt'),
                        'name' => 'mediaLayout',
                        'instructions' => 'Select the layout for displaying media<br>(click on the image to select)',
                        'type' => 'radio',
                        'default_value' => 1,
                        'allow_null' => false,
                        'layout' => 'horizontal',
                        'class' => 'acf-image-select',
                        'choices' => [
                            1 => '<img src="'.Asset::requireUrl("{$componentPath}Assets/media-layout-1.png").'"><p>'.__('Home-Layout', 'flynt').'</p>',
                            2 => '<img src="'.Asset::requireUrl("{$componentPath}Assets/media-layout-2.png").'"><p>'.__('What we do-Layout', 'flynt').'</p>',
                        ],
                    ],
                ]
            ]
        ]
    ];
}
