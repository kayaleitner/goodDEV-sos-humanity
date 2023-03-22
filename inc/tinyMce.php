<?php

/**
 * Moves most relevant editor buttons to the first toolbar
 * and provides config for creating new toolbars, block formats, and style formats.
 * See the TinyMce documentation for more information: https://www.tiny.cloud/docs/
 *
 */

namespace Flynt\TinyMce;

use Flynt\Utils\Asset;

// Add tinyMce styles to editor
add_action('admin_init', function () {
    add_editor_style(Asset::requireUrl('assets/tinymce.scss'));
});

// First Toolbar
add_filter('mce_buttons', function ($buttons) {
    $config = getConfig();
    if ($config && isset($config['toolbars'])) {
        $toolbars = $config['toolbars'];
        if (isset($toolbars['default']) && isset($toolbars['default'][0])) {
            return $toolbars['default'][0];
        }
    }
    return $buttons;
});

// Second Toolbar
add_filter('mce_buttons_2', '__return_empty_array');

add_filter('tiny_mce_before_init', function ($init) {
    $config = getConfig();
    if ($config) {
        if (isset($config['blockformats'])) {
            $init['block_formats'] = getBlockFormats($config['blockformats']);
        }

        if (isset($config['styleformats'])) {
            // Send it to style_formats as true js array
            $init['style_formats'] = json_encode($config['styleformats']);
        }

        if (isset($config['textcolor_map'])) {
            // Send it to textcolor_map as true js array
            $init['textcolor_map'] = json_encode($config['textcolor_map']);
        }
    }
    return $init;
});

add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
    // Load Toolbars and parse them into TinyMCE
    $config = getConfig();
    if ($config && !empty($config['toolbars'])) {
        $toolbars = array_map(function ($toolbar) {
            array_unshift($toolbar, []);
            return $toolbar;
        }, $config['toolbars']);
    }
    return $toolbars;
});

function getBlockFormats($blockFormats)
{
    if (!empty($blockFormats)) {
        $blockFormatStrings = array_map(
            function ($tag, $label) {
                return "${label}=${tag}";
            },
            $blockFormats,
            array_keys($blockFormats)
        );
        return implode(';', $blockFormatStrings);
    }
    return '';
}

function getConfig()
{
    return [
        'textcolor_map' => [
            '000', 'Black',
            'fff', 'White',
            '169b83', 'Blue',
            '272a5f', 'CBEgreen',
            '52b756', 'Green'
        ],
        'blockformats' => [
            __('Paragraph', 'flynt') => 'p',
            __('Heading 1', 'flynt') => 'h1',
            __('Heading 2', 'flynt') => 'h2',
            __('Heading 3', 'flynt') => 'h3',
            // __('Heading 4', 'flynt') => 'h4',
            // __('Heading 5', 'flynt') => 'h5',
            __('Heading 6', 'flynt') => 'h6'
        ],
        'styleformats' => [
            [
                'title' => __('Text', 'flynt'),
                'icon' => '',
                'items' => [
                    [
                        'title' => __('Body', 'flynt'),
                        'classes' => 'body',
                        'selector' => '*'
                    ],
                    [
                        'title' => __('Title Large', 'flynt'),
                        'classes' => 'titleLarge',
                        'selector' => '*'
                    ],
                    [
                        'title' => __('Title Small', 'flynt'),
                        'classes' => 'titleSmall',
                        'selector' => '*'
                    ],
                    [
                        'title' => __('Subtitle', 'flynt'),
                        'classes' => 'subtitle',
                        'selector' => '*'
                    ],
                    [
                        'title' => __('Caption', 'flynt'),
                        'classes' => 'caption',
                        'selector' => '*'
                    ],
                ]
            ],
            [
                'title' => __('Buttons', 'flynt'),
                'icon' => '',
                'items' => [
                    [
                        'title' => __('Button Primary', 'flynt'),
                        'classes' => 'button button--primary',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Outline White', 'flynt'),
                        'classes' => 'button button--outlineWhite',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Outline Black', 'flynt'),
                        'classes' => 'button button--outlineBlack',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Outline Black/White', 'flynt'),
                        'classes' => 'button button--outlineBlackWhite',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Arrow', 'flynt'),
                        'classes' => 'button button--arrow',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Link', 'flynt'),
                        'classes' => 'button button--link',
                        'selector' => 'a'
                    ]
                ]
            ],
        ],
        'toolbars' => [
            'default' => [
                [
                    'formatselect',
                    'styleselect',
                    'bold',
                    'italic',
                    'blockquote',
                    'forecolor',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'alignjustify',
                    '|',
                    'hr',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'removeformat'
                ]
            ],
            'basic' => [
                [
                    'formatselect',
                    'styleselect',
                    'bold',
                    'italic',
                    'blockquote',
                    'forecolor',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'alignjustify',
                    '|',
                    'hr',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'removeformat'
                ]
            ]
        ]
    ];
}
