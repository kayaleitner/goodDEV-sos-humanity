<?php

namespace Flynt\Components\BlockImageTitleQuote;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockImageTitleQuote',
        'label' => 'Block: Image Title Quote (Home)',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Top Title', 'flynt'),
                'name' => 'topTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Bottom Title', 'flynt'),
                'name' => 'bottomTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'accordionContent',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Image', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'mime_types' => 'jpg,jpeg,png',
                'required' => 1,
                'instructions' => __('Image-Format: JPG, PNG. Recommended resolution greater than 2048 x 800 px.', 'flynt'),
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'contentTitle',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
            ],
            [
                'label' => __('Text', 'flynt'),
                'name' => 'contentText',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
            ],
            [
                'label' => __('CTA Link', 'flynt'),
                'name' => 'ctaLink',
                'type' => 'link',
                'return_format' => 'array'
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
                'layout' => 'block',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                ]
            ]
        ]
    ];
}
