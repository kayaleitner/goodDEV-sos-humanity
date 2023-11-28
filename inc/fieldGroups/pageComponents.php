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
                    // Components\BlockAnchor\getACFLayout(),
                    // Components\BlockCta\getACFLayout(),
                    // Components\BlockWysiwyg\getACFLayout(),
                    // Components\BlockImage\getACFLayout(),
                    // Components\BlockInterstitial\getACFLayout(),
                    // Components\BlockListingMap\getACFLayout(),
                    // Components\BlockPulloutQuote\getACFLayout(),
                    // Components\BlockImageTextFull\getACFLayout(),
                    // Components\BlockVideoOembed\getACFLayout(),
                    // Components\BlockHeroContactForm\getACFLayout(),
                    // Components\BlockHeroIllustrationLottie\getACFLayout(),
                    // Components\BlockListingAuto\getACFLayout(),
                    // Components\BlockListingSelector\getACFLayout(),
                    // Components\ReusableComponent\getACFLayout(),
                    // Components\BlockScrollytelling\getACFLayout(),
                    // Components\BlockScrollytellingImage\getACFLayout(),
                    // Components\BlockSeparatorLine\getACFLayout(),
                    // Components\BlockSliderImages\getACFLayout(),
                    // Components\BlockSliderText\getACFLayout(),

                    // Breathe Cities
                    Components\BlockHeader\getACFLayout(),
                    Components\BlockPartners\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockCarouselCities\getACFLayout(),
                    Components\BlockCarousel\getACFLayout(),
                    Components\BlockCards\getACFLayout(),
                    Components\BlockStatistics\getACFLayout(),
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
