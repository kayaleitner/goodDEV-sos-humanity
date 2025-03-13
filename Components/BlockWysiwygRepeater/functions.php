<?php

namespace Flynt\Components\BlockWysiwygRepeater;

use Flynt\FieldVariables;

add_action('acf/render_field_settings/type=true_false', function( $field ) {
    if ($field['name'] === 'formOrText') {
        echo '<pre>';
        print_r($field);
        echo '</pre>';
    }
});

function getACFLayout()
{
    return [
        'name' => 'BlockWysiwygRepeater',
        'label' => 'Block: Rich Text Column',
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
                'name' => 'preContent',
                'type' => 'text',
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Text Editor', 'flynt'),
                'name' => 'texteditorTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Text Editor Panels', 'flynt'),
                'name' => 'contentPanels',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Text Editor', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Form or Text Editor', 'flynt'),
                        'name' => 'formOrText',
                        'type' => 'true_false',
                        // regler
                        'ui' => 1,
                        'ui_on_text' => 'Form',
                        'ui_off_text' => 'Text',
                        'default_value' => 0,
                    ],
                    [
                        'label' => __('Fundraising Box Form Hash', 'flynt'),
                        'name' => 'formId',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_BlockWysiwygRepeater_contentPanels_formOrText',
                                    'operator' => '==',
                                    'value' => '1',
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => __('Text Editor', 'flynt'),
                        'name' => 'panelTexteditor',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_BlockWysiwygRepeater_contentPanels_formOrText',
                                    'operator' => '==',
                                    'value' => '0',
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
                'layout' => 'block',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                ]
            ]
        ]
    ];
}
