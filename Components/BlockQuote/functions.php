<?php

namespace Flynt\Components\BlockQuote;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockQuote',
        'label' => 'Block: Quote',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Author', 'flynt'),
                'name' => 'author',
                'type' => 'text'
            ],
            [
                'label' => __('Quote Size', 'flynt'),
                'name' => 'quoteSize',
                'type' => 'radio',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'quote-big' => __('Big', 'flynt'),
                    'quote-medium' => __('Medium (Default)', 'flynt'),
                    'quote-small' => __('Small', 'flynt)')
                ],
                'default_value' => 'quote-medium',
                'wrapper' =>  [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Quote', 'flynt'),
                'name' => 'quote',
                'type' => 'textarea'
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
