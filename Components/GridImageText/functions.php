<?php

namespace Flynt\Components\GridImageText;

use Flynt\Utils\Asset;
use Flynt\ComponentManager;
use Flynt\FieldVariables;
use Timber;

// add_filter('Flynt/addComponentData?name=NavigationFooterColumns', function ($data) {
//     $componentManager = ComponentManager::getInstance();
//     $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

//     if (!empty($data['social'])) {
//         $data['social'] = array_map(function ($item) use ($componentPath) {
//             $item['icon'] = Asset::getContents("{$componentPath}Assets/{$item['platform']['value']}.svg");
//             return $item;
//         }, $data['social']);
//     }
//     return $data;
// });

function getACFLayout()
{
    return [
        'name' => 'GridImageText',
        'label' => 'Grid: Image Text',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContent',
                'type' => 'text',
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Items', 'flynt'),
                'name' => 'items',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => 'Add item',
                'sub_fields' => [
                    [
                        'label' => __('Tile Link', 'flynt'),
                        'name' => 'itemLink',
                        'type' => 'link',
                        'return_format' => 'array',
                        'wrapper' =>  [
                            'width' => '50'
                        ]
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'instructions' => __('Image-Format: JPG, PNG, SVG', 'flynt'),
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => [
                            'width' => '50'
                        ],
                    ],
                    [
                        'label' => __('Text or Title', 'flynt'),
                        'name' => 'isTitle',
                        'type' => 'true_false',
                        'instructions' => __('Do you want to use the text as a title or as a paragraph?', 'flynt'),
                        'default_value' => 1,
                        'wrapper' => [
                            'width' => '100'
                        ],
                        'ui' => 1,
                        'ui_on_text' => __('Title', 'flynt'),
                        'ui_off_text' => __('Text', 'flynt')
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'itemTitle',
                        'type' => 'text',
                        'required' => 1,
                        'wrapper' => [
                            'width' => '70'
                        ],
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_GridImageText_items_isTitle',
                                    'operator' => '==',
                                    'value' => '1'
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => __('Title Alignment', 'flynt'),
                        'name' => 'titleAlignment',
                        'type' => 'button_group',
                        'choices' => [
                            'left' => '<i class=\'dashicons dashicons-editor-alignleft\' title=\'Align title left\'></i>',
                            'center' => '<i class=\'dashicons dashicons-editor-aligncenter\' title=\'Align title center\'></i>'
                        ],
                        'default_value' => 'left',
                        'wrapper' => [
                            'width' => '15'
                        ],
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_GridImageText_items_isTitle',
                                    'operator' => '==',
                                    'value' => '1'
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => __('Title Size', 'flynt'),
                        'name' => 'titleSize',
                        'type' => 'button_group',
                        'choices' => [
                            'h4' => '<i class=\'dashicons dashicons-plus-alt2\' title=\'Title Big\'></i>',
                            'paragraph' => '<i class=\'dashicons dashicons-minus\' title=\'Title Small\'></i>'
                        ],
                        'default_value' => 'h4',
                        'wrapper' => [
                            'width' => '15'
                        ],
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_GridImageText_items_isTitle',
                                    'operator' => '==',
                                    'value' => '1'
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'itemText',
                        'type' => 'wysiwyg',

                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => '100'
                        ],
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_GridImageText_items_isTitle',
                                    'operator' => '==',
                                    'value' => '0'
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => __('Instagram', 'flynt'),
                        'name' => 'itemSocial',
                        'type' => 'link',
                        'return_format' => 'array',
                        'wrapper' =>  [
                            'width' => '50'
                        ]
                    ],
                    [
                        'label' => __('Email', 'flynt'),
                        'name' => 'itemEmail',
                        'type' => 'link',
                        'return_format' => 'array',
                        'wrapper' =>  [
                            'width' => '50'
                        ]
                    ],
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
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                    [
                        'label' => __('Columns', 'flynt'),
                        'name' => 'columns',
                        'type' => 'number',
                        'default_value' => 3,
                        'min' => 2,
                        'max' => 4,
                        'step' => 1
                    ]
                ]
            ]
        ]
    ];
}
