<?php

namespace Flynt\Components\SliderText;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=SliderText', function ($data) {
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'sliderText',
        'label' => 'Slider: Text',
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
                'name' => 'blockTitle',
                'type' => 'text',
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Text Boxes', 'flynt'),
                'name' => 'contentBoxes',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Box', 'flynt'),
                'sub_fields' => [
                    [
                    'label' => __('Box Background Color', 'flynt'),
                    'name' => 'panelTextboxbgcolor',
                    'type' => 'select',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'choices' => [
                        'var(--primary)' => __('Primary', 'flynt'),
                        'var(--secondary)' => __('Secondary', 'flynt')
                    ],
                    'return_format' => 'value',
                    ],
                    // [
                    //     'label' => __('Box Background Color', 'flynt'),
                    //     'name' => 'panelTextboxbgcolor',
                    //     'type' => 'color_picker',
                    // ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'panelTextbox',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                    ],
                    [
                        'label' => __('Logo/Icon', 'flynt'),
                        'name' => 'panelLogo',
                        'type' => 'image',
                        'preview_size' => 'small',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'required' => 0,
                        'mime_types' => 'jpg,jpeg,png,svg'
                    ],
                    [
                        'label' => __('Author', 'flynt'),
                        'name' => 'panelAuthor',
                        'type' => 'text'
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
                    [
                        'label' => __('Enable Autoplay', 'flynt'),
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1
                    ],
                    [
                        'label' => __('Autoplay Speed (in milliseconds)', 'flynt'),
                        'name' => 'autoplaySpeed',
                        'type' => 'number',
                        'min' => 2000,
                        'step' => 1,
                        'default_value' => 4000,
                        'required' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'autoplay',
                                    'operator' => '==',
                                    'value' => 1
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ]
    ];
}
