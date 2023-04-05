<?php

namespace Flynt\Components\BlockNewsletter;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\Shortcodes;
use Flynt\ComponentManager;
use Flynt\FieldVariables;
use Timber\Timber;

// function getACFLayout()
// {
//     return [
//         'name' => 'BlockNewsletter',
//         'label' => __('Block: Newsletter', 'flynt'),
//         'sub_fields' => [
//             [
//                 'label' => __('General', 'flynt'),
//                 'name' => 'generalTab',
//                 'type' => 'tab',
//                 'placement' => 'top',
//                 'endpoint' => 0,
//             ],
//             [
//                 'label' => __('Content', 'flynt'),
//                 'name' => 'contentHtml',
//                 'type' => 'wysiwyg',
//                 'delay' => 1,
//                 'media_upload' => 0,
//                 'required' => 0,
//             ],
//             [
//                 'label' => __('Options', 'flynt'),
//                 'name' => 'optionsTab',
//                 'type' => 'tab',
//                 'placement' => 'top',
//                 'endpoint' => 0
//             ],
//         ]
//     ];
// }

Options::addTranslatable('BlockNewsletter', [
    [
        'label' => __('General', 'flynt'),
        'name' => 'generalTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Content', 'flynt'),
        'name' => 'contentHtml',
        'type' => 'wysiwyg',
        'delay' => 1,
        'media_upload' => 0,
        'required' => 0,
        'wrapper' => [
            'width' => 50,
        ]
    ],
    [
        'label' => __('Contact Form', 'flynt'),
        'name' => 'contactForm',
        'type' => 'wysiwyg',
        'delay' => 1,
        'media_upload' => 0,
        'required' => 0,
        'wrapper' => [
            'width' => 50,
        ]
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
            FieldVariables\getColorBackground(),
            FieldVariables\getNavStyle('dark-blur'),
        ]
    ]
]);
