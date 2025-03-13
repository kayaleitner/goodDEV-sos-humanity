<?php

namespace Flynt\Components\WidgetPetition;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'WidgetPetition',
        'label' => 'Widget: Petition',
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
                'name' => 'preContentHtml',
                'type' => 'text',
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'media_upload' => 0,
            ],
            [
                'label' => __('Dangeriously set HTML', 'flynt'),
                'name' => 'html',
                'type' => 'textarea',
                'instructions' => __('Enter the HTML for the petition form.', 'flynt'),
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
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                ]
            ]
        ]
    ];
}
