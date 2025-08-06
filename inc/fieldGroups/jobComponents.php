<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {

    ACFComposer::registerFieldGroup([
        'name' => 'jobMeta',
        'title' => 'Meta',
        'style' => '',
        'menu_order' => 3,
        'position' => 'side',
        'fields' => [
            [
                'label' => __('Position Offen?', 'flynt'),
                'name' => 'positionOpen',
                'type' => 'true_false',
                'message' => __('Wenn angekreut, wird im Listing als offene Position angezeigt. ', 'flynt'),
            ],
            [
                'label' => __('Vorschau', 'flynt'),
                'name' => 'jobDescription',
                'type' => 'textarea',
                'message' => __('Kurze Beschreibung der Position', 'flynt'),
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'job',
                ],
            ],
        ],
    ]);

    ACFComposer::registerFieldGroup([
        'name' => 'jobComponents',
        'title' => 'Job Components',
        'style' => 'seamless',
        'menu_order' => 2,
        'fields' => [
            [
                'name' => 'jobComponents',
                'label' => __('Job Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\HeroImageText\getACFLayout(),
                    Components\AccordionDefault\getACFLayout(),
                    Components\BlockFactSheet\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockTextImageCrop\getACFLayout(),
                    Components\BlockImageTextPageLink\getACFLayout(),
                    Components\BlockQuote\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockWysiwygRepeater\getACFLayout(),
                    Components\ListNumbers\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\SliderVoices\getACFLayout(),
                    Components\FullBleedImageTitleTextParallax\getACFLayout(),
                    Components\GridIcons\getACFLayout(),
                    Components\GridImageText\getACFLayout(),
                    Components\GridPostsSelector\getACFLayout()
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'job'
                ],
            ],
        ],
    ]);
});
