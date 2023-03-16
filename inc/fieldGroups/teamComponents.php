<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'teamMeta',
        'title' => 'Personnel Info',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Info', 'flynt'),
                'name' => 'infoTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Position', 'flynt'),
                'name' => 'position',
                'type' => 'text',
                'wrapper' => [
                    'width' => 100
                ]
            ],
            [
                'label' => __('Bio', 'flynt'),
                'name' => 'bio',
                'type' => 'wysiwyg',
                'wrapper' => [
                    'width' => 100
                ]
            ],
            [
                'label' => __('Social Media', 'flynt'),
                'name' => 'socialmediaTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Social Platform', 'flynt'),
                'name' => 'socials',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Social Link', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Platform', 'flynt'),
                        'name' => 'platform',
                        'type' => 'select',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 1,
                        'ajax' => 0,
                        'return_format' => 'array',
                        'choices' => [
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter'
                        ]
                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'url',
                        'type' => 'url',
                        'required' => 1
                    ],
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'team',
                ],
            ],
        ],
    ]);
    // ACFComposer::registerFieldGroup([
    //     'name' => 'teamComponents',
    //     'title' => __('Team Components', 'flynt'),
    //     'style' => 'seamless',
    //     'fields' => [
    //         [
    //             'name' => 'teamComponents',
    //             'label' => __('Team Components', 'flynt'),
    //             'type' => 'flexible_content',
    //             'button_label' => __('Add Component', 'flynt'),
    //             'layouts' => [
    //                 Components\BlockAnchor\getACFLayout(),
    //                 Components\BlockCollapse\getACFLayout(),
    //                 Components\BlockImage\getACFLayout(),
    //                 Components\BlockImageText\getACFLayout(),
    //                 Components\BlockPulloutQuote\getACFLayout(),
    //                 Components\BlockVideoOembed\getACFLayout(),
    //                 Components\BlockWysiwyg\getACFLayout(),
    //                 Components\SliderImages\getACFLayout(),
    //                 Components\ReusableComponent\getACFLayout(),
    //             ],
    //         ],
    //     ],
    //     'location' => [
    //         [
    //             [
    //                 'param' => 'post_type',
    //                 'operator' => '==',
    //                 'value' => 'team',
    //             ],
    //         ],
    //     ],
    // ]);
});
