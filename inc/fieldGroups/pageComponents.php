<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'pageExcerpt',
        'title' => 'Page Excerpt',
        'style' => '',
        'menu_order' => 3,
        'position' => 'side',
        'fields' => [
            [
                'label' => __('Excerpt', 'flynt'),
                'name' => 'pageExcerpt',
                'type' => 'textarea',
                'maxlength' => 150,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'pageComponents',
        'title' => 'Page Components',
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'pageComponents',
                'label' => __('Page Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    // Components\BlockAnchor\getACFLayout(),
                    Components\HeroImageText\getACFLayout(),
                    Components\AccordionDefault\getACFLayout(),
                    Components\BlockFactSheet\getACFLayout(),
                    Components\BlockTicker\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockTextImageCrop\getACFLayout(),
                    Components\BlockImageTextPageLink\getACFLayout(),
                    Components\BlockImageTextTitle\getACFLayout(),
                    Components\BlockImageTitleQuote\getACFLayout(),
                    Components\BlockIframe\getACFLayout(),
                    Components\BlockQuote\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockWysiwygRepeater\getACFLayout(),
                    Components\CtaDonate\getACFLayout(),
                    Components\ListNumbers\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\SliderVoices\getACFLayout(),
                    Components\FullBleedImageTitleTextParallax\getACFLayout(),
                    Components\GridIcons\getACFLayout(),
                    Components\GridImageText\getACFLayout(),
                    Components\GridPostsLatest\getACFLayout(),
                    Components\GridJobs\getACFLayout(),
                    Components\GridPostsSelector\getACFLayout(),
                    Components\WidgetPetition\getACFLayout(),
                    Components\ProgressDonation\getACFLayout(),
                    Components\DonationSlider\getACFLayout(),
                    Components\BlockContinousDonation\getACFLayout(),
                ]
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page'
                ]
            ]
        ]
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'appearance',
        'title' => 'Appearance',
        'style' => '',
        'menu_order' => 4,
        'position' => 'side',
        'fields' => [
            [
                'label' => 'Menu Text Color',
                'name' => 'menuTextColor',
                'type' => 'button_group',
                'choices' => [
                    'lightText' => '<i  title=\'Light Text\'>light</i>',
                    'darkText' => '<i  title=\'Dark Text\'>dark</i>',
                ],
                'wrapper' => [
                    'width' => '100',
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ],
            ],
        ],
    ]);
});
