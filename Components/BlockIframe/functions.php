<?php

namespace Flynt\Components\BlockIframe;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'blockIframe',
        'label' => 'Block: Iframe',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'text',
                'instructions' => __('Enter the title of the iframe.', 'flynt'),
            ],
            [
                'label' => __('Iframe Url', 'flynt'),
                'name' => 'iframeUrl',
                'type' => 'url',
                'required' => 1,
                'instructions' => __('Paste the iframe code here.', 'flynt'),
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    // FieldVariables\getTheme(),
                    // FieldVariables\getColorBrandBackground(),
                    // FieldVariables\getColorBrandText(),
                    FieldVariables\getMaxWidthContainer(),
                    FieldVariables\getPaddingTopBottom(),
                    FieldVariables\getPaddingLeftRight(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                ]
            ]
        ]
    ];
}
