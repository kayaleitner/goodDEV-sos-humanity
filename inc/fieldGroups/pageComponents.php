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
        'fields' => [
            [
                'name' => 'pageComponents',
                'label' => __('Page Blocks', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Block', 'flynt'),
                'layouts' => [
                    Components\ArchiveInsights\getACFLayout(),
                    Components\ArchivePeople\getACFLayout(),
                    Components\ArchiveProjects\getACFLayout(),
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockCta\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageTextFull\getACFLayout(),
                    Components\BlockPulloutQuote\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\HeroContactForm\getACFLayout(),
                    Components\HeroIllustration\getACFLayout(),
                    Components\HeroIllustrationLottie\getACFLayout(),
                    Components\HeroScrollySplit\getACFLayout(),
                    Components\ListComponents\getACFLayout(),
                    Components\ListingInsights\getACFLayout(),
                    Components\ListingMap\getACFLayout(),
                    Components\ListingProjects\getACFLayout(),
                    Components\ListingPeople\getACFLayout(),
                    Components\ReusableComponent\getACFLayout(),
                    Components\Scrollytelling\getACFLayout(),
                    Components\ScrollytellingImage\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\SliderText\getACFLayout(),
                    Components\ListingFlex\getACFLayout(),
                    Components\ListingManual\getACFLayout(),
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
