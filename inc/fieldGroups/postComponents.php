<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {

    ACFComposer::registerFieldGroup([
        'name' => 'meta',
        'title' => 'Meta',
        'style' => '',
        'menu_order' => 3,
        'position' => 'side',
        'fields' => [
            [
                'label' => 'Untertitel',
                'name' => 'text',
                'type' => 'text',
            ],
            [
                'label' => 'Datum',
                'name' => 'date',
                'type' => 'date_picker',
                'instructions' => 'Überschreibt das Veröffentlichungsdatum (z.B. für zukünftige Veranstaltungen)',
                'display_format' => 'F j, Y',
                'return_format' => 'Ymd',
                'first_day' => 1,
                'required' => 1
            ],
            [
                'label' => 'Datei',
                'name' => 'file',
                'type' => 'file',
                'instructions' => 'Ermöglicht einen Datei-Download in der Übersicht',
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
        'title' => 'Post Components',
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'postComponents',
                'label' => __('Post Blocks', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Block', 'flynt'),
                'layouts' => [
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
                    Components\ListNumbers\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\SliderVoices\getACFLayout(),
                    Components\FullBleedImageTitleTextParallax\getACFLayout(),
                    Components\GridIcons\getACFLayout(),
                    Components\GridImageText\getACFLayout(),
                    Components\GridPostsSelector\getACFLayout(),
                    Components\WidgetPetition\getACFLayout(),
                    Components\ProgressDonation\getACFLayout(),
                    // Components\DonationSlider\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post'
                ],
            ],
        ],
    ]);
});
