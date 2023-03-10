<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'projectMeta',
        'title' => 'Factsheet',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Latitude', 'flynt'),
                'name' => 'latitude',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50
                ]
            ],
            [
                'label' => __('Longitude', 'flynt'),
                'name' => 'longitude',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50
                ]
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'projectComponents',
        'title' => __('Project Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'projectComponents',
                'label' => __('Project Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\ReusableComponent\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
                ],
            ],
        ],
    ]);
});
