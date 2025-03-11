<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function (): void {
    ACFComposer::registerFieldGroup([
        'name' => 'reusableComponents',
        'title' => __('Fixed Blocks', 'flynt'),
        'style' => 'seamless',
        'menu_order' => 1,
        'fields' => [
            [
                'name' => 'reusableComponents',
                'label' => __('Fixed Blocks', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Block', 'flynt'),
                'layouts' => [
                    // Components\BlockWysiwyg\getACFLayout(),
                    // Components\BlockImageText\getACFLayout(),
                    // Components\BlockCards\getACFLayout(),
                    // Components\BlockCarousel\getACFLayout(),
                    // Components\BlockStatistics\getACFLayout(),
                ],
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'reusable-components'
                ],
            ]
        ]
    ]);
});
