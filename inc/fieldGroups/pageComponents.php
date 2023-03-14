<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'pageMeta',
        'title' => 'Page Options',
        'style' => '',
        'menu_order' => 1,
        'position' => 'side',
        'fields' => [
            [
                'label' => __('Page Header', 'flynt'),
                'instructions' => __('Display page header. When disabled, you can add another block acting as header', 'flynt'),
                'name' => 'displayBanner',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
                'default_value' => 1,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
            [
                'label' => __('Intro', 'flynt'),
                'instructions' => __('Maximum 200 characters (~ 30 words).', 'flynt'),
                'name' => 'intro',
                'type' => 'textarea',
                'maxlength' => 200,
                'wrapper' => [
                    'width' => 100
                ]
            ],
        ],
        'location' => [
            [
                // [
                //     'param' => 'post_type',
                //     'operator' => '==',
                //     'value' => 'page'
                // ],
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
                    'value' => 'reusable-components'
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'pageComponents',
        'title' => __('Page Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'pageComponents',
                'label' => __('Page Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockImageTextFull\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\GridImageText\getACFLayout(),
                    Components\HeroIllustration\getACFLayout(),
                    Components\HeroLottieAnimation\getACFLayout(),
                    Components\HeroScrollySplit\getACFLayout(),
                    Components\ListComponents\getACFLayout(),
                    Components\ListingInsights\getACFLayout(),
                    Components\ListingMap\getACFLayout(),
                    Components\ListingProjects\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\ReusableComponent\getACFLayout(),
                    Components\SliderText\getACFLayout(),
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
                    'value' => 'reusable-components'
                ],
            ],
        ],
    ]);
});
