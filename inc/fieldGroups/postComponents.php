<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'projectSidebar',
        'title' => 'Sidebar',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Facts', 'flynt'),
                'name' => 'factsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Fact Box Title', 'flynt'),
                'name' => 'factBoxTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Facts', 'flynt'),
                'name' => 'facts',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Fact', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    [
                        'label' => __('Fact', 'flynt'),
                        'name' => 'factTextarea',
                        'type' => 'textarea',
                        'maxlength' => 180
                    ]
                ]
            ],
            [
                'label' => __('Download', 'flynt'),
                'name' => 'downloadTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Download Link Label', 'flynt'),
                'name' => 'downloadLinkLabel',
                'type' => 'text',
                'wrapper' =>  [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Download Link', 'flynt'),
                'name' => 'download',
                'type' => 'file',
                'return_format' => 'array',
                'wrapper' =>  [
                    'width' => 50,
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'postComponents',
        'title' => __('Post Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'postComponents',
                'label' => __('Post Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockPulloutQuote\getACFLayout(),
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
                    'value' => 'post',
                ],
            ],
        ],
    ]);
});
