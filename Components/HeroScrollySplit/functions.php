<?php

namespace Flynt\Components\HeroScrollySplit;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'heroScrollySplit',
        'label' => __('Scrollytelling: Hero', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Slides', 'flynt'),
                'name' => 'slidesTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Slides',
                'name' => 'slides',
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'table',
                'pagination' => 0,
                'min' => 2,
                'max' => 2,
                'collapsed' => '',
                'button_label' => 'Add Row',
                'sub_fields' => array(
                    array(
                        'label' => 'Image',
                        'name' => 'image',
                        'aria-label' => '',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'preview_size' => 'medium',
                        'parent_repeater' => 'field_63f4adae8cef0',
                    ),
                    array(
                        'label' => 'Text 1',
                        'name' => 'text_1',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_63f4adae8cef0',
                    ),
                    array(
                        'label' => 'Text 2',
                        'name' => 'text_2',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_63f4adae8cef0',
                    )
                ),
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
                    FieldVariables\getNavStyle('dark-clear'),
                ]
            ]
        ]

    ];
}
