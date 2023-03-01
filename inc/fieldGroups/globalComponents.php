<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'optionsMeta',
        'title' => 'Options Meta',
        'style' => '',
        'menu_order' => 1,
        'fields' => [
            [
                'label' => __('Theme Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Theme', 'flynt'),
                'name' => 'globalThemeOption',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'choices' => [
                    'cbe' => __('CrossBoundary Energy', 'flynt'),
                    'cbg' => __('CrossBoundary Group', 'flynt'),
                ],
                'default_value' => 'cbe',
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'GlobalOptions-Default',
                ],
                // [
                //     'param' => 'post_type',
                //     'operator' => '==',
                //     'value' => 'page'
                // ],
            ]
        ],
    ]);
});
