<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'optionsMeta',
        'title' => 'Theme Options',
        'style' => '',
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Theme Name', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('', 'flynt'),
                'name' => 'globalThemeOption',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'choices' => [
                    'none' => __('None', 'flynt'),
                ],
                'default_value' => 'none',
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'GlobalOptions-Default',
                ],
            ]
        ],
    ]);
});
