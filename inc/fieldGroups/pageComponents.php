<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function (): void {
    ACFComposer::registerFieldGroup([
        'name' => 'pageMeta',
        'title' => 'Page Options',
        'style' => '',
        'menu_order' => 1,
        'position' => 'side',
        'fields' => [
            [
                'label' => __('Navigation is on light', 'flynt'),
                'instructions' => __('If enabled, the navigation is rendered with a blue text on light background', 'flynt'),
                'name' => 'navOnLight',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
                'default_value' => 0,
                'wrapper' => [
                    'width' => 100,
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
                    Components\BlockCenterText\getACFLayout(),
                    Components\BlockCarouselCities\getACFLayout(),
                    Components\BlockCarousel\getACFLayout(),
                    Components\BlockCards\getACFLayout(),
                    Components\BlockStatistics\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockScrollySlides\getACFLayout(),
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
