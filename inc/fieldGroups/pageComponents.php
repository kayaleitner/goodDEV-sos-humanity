<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function (): void {
    ACFComposer::registerFieldGroup([
        'name' => 'pageHeader',
        'title' => 'Page Header',
        'style' => 'seamless',
        'menu_order' => 0,
        'position' => 'normal',
        'fields' => [
            [
                'label' => __('Display Page Header', 'flynt'),
                'instructions' => __('If enabled, the navigation is rendered with a blue text on light background', 'flynt'),
                'name' => 'displayPageHeader',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
                'default_value' => 1,
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Logo is on dark', 'flynt'),
                'instructions' => __('If enabled, the dark version of the logo is rendered', 'flynt'),
                'name' => 'logoOnDark',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
                'default_value' => 0,
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Header', 'flynt'),
                'name' => 'header',
                'type' => 'group',
                // 'layout' => '',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'displayPageHeader',
                            'operator' => '==',
                            'value' => 1
                        ]
                    ]
                ],
                'sub_fields' => [
                    [
                        'label' => __('Long Title', 'flynt'),
                        'instructions' => __('Displayed as H1, falls back to page title if none provided', 'flynt'),
                        'name' => 'longTitle',
                        'type' => 'textarea',
                        "rows" => 6,
                        'new_lines' => 'br',
                        'wrapper' => [
                            'width' => 33,
                        ],
                    ],
                    [
                        'label' => __('Intro', 'flynt'),
                        'instructions' => __('Short Paragraph', 'flynt'),
                        'name' => 'intro',
                        'type' => 'textarea',
                        "rows" => 6,
                        'new_lines' => 'br',
                        'wrapper' => [
                            'width' => 33,
                        ],
                    ],
                    [
                        'label' => __('Shape', 'flynt'),
                        'instructions' => __('Shape for the animation - should consist of a single thin colored stroke - but open for experiments', 'flynt'),
                        'name' => 'shape',
                        'type' => 'image',
                        'wrapper' => [
                            'width' => 20,
                        ],
                    ],
                    [
                        'label' => __('Stretch', 'flynt'),
                        'instructions' => __('Stretch of the animation', 'flynt'),
                        'name' => 'stretch',
                        'type' => 'number',
                        'default_value' => 20,
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                        'append' => '%',
                        'wrapper' => [
                            'width' => 10,
                        ],
                    ],
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'post'
                ],
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'project'
                ],
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'people'
                ],
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'reusable-components'
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'pageComponents',
        'title' => __('Page Blocks', 'flynt'),
        'style' => 'seamless',
        'menu_order' => 1,
        'fields' => [
            [
                'name' => 'pageComponents',
                'label' => __('Page Blocks', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Block', 'flynt'),
                'layouts' => [                 
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockCards\getACFLayout(),
                    Components\BlockCarousel\getACFLayout(),
                    Components\BlockCarouselTextImage\getACFLayout(),
                    Components\BlockListingAuto\getACFLayout(),
					Components\BlockListingOembed\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'post'
                ],
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'project'
                ],
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'people'
                ],
                [
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'reusable-components'
                ],
            ],
        ],
    ]);
});
