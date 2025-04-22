<?php

/**
 * Moves most relevant editor buttons to the first toolbar
 * and provides config for creating new toolbars, block formats, and style formats.
 * See the TinyMce documentation for more information: https://www.tiny.cloud/docs/
 */

namespace Flynt\TinyMce;

use Flynt\Utils\Asset;

// Add tinyMce styles to editor.
add_action('admin_init', function () {
    add_editor_style(Asset::requireUrl('assets/tinymce.css'));
});

// First Toolbar.
add_filter('mce_buttons', function (array $buttons) {
    $config = getConfig();
    if ($config && isset($config['toolbars'])) {
        $toolbars = $config['toolbars'];
        if (isset($toolbars['default']) && isset($toolbars['default'][0])) {
            return $toolbars['default'][0];
        }
    }

    return $buttons;
});

// Second Toolbar.
add_filter('mce_buttons_2', '__return_empty_array');

add_filter('tiny_mce_before_init', function (array $mceInit): array {
    $config = getConfig();

    if ($config !== []) {
        if (isset($config['blockformats'])) {
            $mceInit['block_formats'] = getBlockFormats($config['blockformats']);
        }

        if (isset($config['styleformats'])) {
            // Send it to style_formats as true js array
            $mceInit['style_formats'] = json_encode($config['styleformats']);
        }

        if (isset($config['entities'])) {
            $entityString = getEntities($config['entities']);
            if ($entityString !== '' && $entityString !== '0') {
                $mceInit['entities'] = implode(',', [$mceInit['entities'] ?? '', $entityString]);
            }
        }

        if (isset($config['entity_encoding'])) {
            $mceInit['entity_encoding'] = getEntityEncoding($config['entity_encoding']);
        }

        if (isset($config['textcolor_map'])) {
            // error_log(json_encode($config['textcolor_map']));
            // Send it to textcolor_map as true js array
            $mceInit['textcolor_map'] = json_encode($config['textcolor_map']);
        }
    }

    return $mceInit;
}, 10);

add_filter('acf/fields/wysiwyg/toolbars', function (array $toolbars) {
    // Load Toolbars and parse them into TinyMCE.
    $config = getConfig();
    if ($config && !empty($config['toolbars'])) {
        return array_map(function ($toolbar) {
            array_unshift($toolbar, []);
            return $toolbar;
        }, $config['toolbars']);
    }

    return $toolbars;
});

function getBlockFormats($blockFormats): string
{
    if (!empty($blockFormats)) {
        $blockFormatStrings = array_map(
            function ($tag, $label): string {
                return "{$label}={$tag}";
            },
            $blockFormats,
            array_keys($blockFormats)
        );
        return implode(';', $blockFormatStrings);
    }

    return '';
}

function getPalette()
{
    $cssContent = file_get_contents(get_template_directory() . '/assets/styles/_colors.css');
    $parser = new \Sabberworm\CSS\Parser($cssContent);
    $cssDocument = $parser->parse();
    $colors = [];
    $theme = get_option('options_globalThemeOption');

    foreach ($cssDocument->getAllDeclarationBlocks() as $block) {
        foreach ($block->getSelectors() as $selector) {
            if ($selector->getSelector() === ':root' || $selector->getSelector() === "[data-theme='$theme']") {
                foreach ($block->getRules() as $rule) {
                    $outputFormat = \Sabberworm\CSS\OutputFormat::create();
                    $slug = str_replace('-', '', $rule->getRule());
                    if (str_contains($rule->getValue()->render($outputFormat), '#')) {
                        $colors[] = str_replace('#', '', $rule->getValue()->render(\Sabberworm\CSS\OutputFormat::create()));
                        $colors[] = $slug;
                    }
                }
            }
        }
    }
    return $colors;
}

function getEntities($entities): string
{
    if (!empty($entities)) {
        $entityString = array_map(
            function ($name, $code): string {
                return "{$code},{$name}";
            },
            $entities,
            array_keys($entities)
        );
        return implode(',', $entityString);
    }

    return '';
}

function getEntityEncoding($entityEncoding): string
{
    if (!empty($entityEncoding)) {
        return $entityEncoding;
    }

    return 'raw';
}

function getConfig(): array
{

    return [
        'textcolor_map' => getPalette(),
        'blockformats' => [
            __('Paragraph', 'flynt') => 'p',
            __('Preformatted', 'flynt') => 'pre',
            __('Page Title (H1)', 'flynt') => 'h1',
            __('Section Title (H2)', 'flynt') => 'h2',
            __('Content Title (H3)', 'flynt') => 'h3',
        ],
        
        'blockformats' => [
            'Paragraph' => 'p',
            'Heading 1' => 'h1',
            'Heading 2' => 'h2',
            'Heading 3' => 'h3',
            'Heading 4' => 'h4',
            'Heading 5' => 'h5',
            'Heading 6' => 'h6'
        ],
        'styleformats' => [
            [
                'title' => 'Headings',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Heading 1',
                        'classes' => 'h1',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Heading 2',
                        'classes' => 'h2',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Heading 3',
                        'classes' => 'h3',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Heading 4',
                        'classes' => 'h4',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Heading 5',
                        'classes' => 'h5',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Heading 6',
                        'classes' => 'h6',
                        'selector' => '*'
                    ],
                ]
            ],
            [
                'title' => 'Bodies',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Body 2',
                        'classes' => 'body-2',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Caption',
                        'classes' => 'caption',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Quote Small',
                        'classes' => 'quote-small',
                        'selector' => '*'
                    ],
                ]
            ],
            [
                'title' => 'Quotes',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Quote Big',
                        'classes' => 'quote-big',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Quote Medium',
                        'classes' => 'quote-medium',
                        'selector' => '*'
                    ],
                    [
                        'title' => 'Quote Small',
                        'classes' => 'quote-small',
                        'selector' => '*'
                    ],
                ]
            ],
            [
                'title' => 'Buttons',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Button Blue',
                        'classes' => 'button',
                        'selector' => 'a,button'
                    ],
                    [
                        'title' => 'Button Yellow',
                        'classes' => 'button--yellow',
                        'selector' => '.button'
                    ],
                    // [
                    //     'title' => 'Button Ghost',
                    //     'classes' => 'button--ghost',
                    //     'selector' => '.button'
                    // ],
                    // [
                    //     'title' => 'Button Small',
                    //     'classes' => 'button--small',
                    //     'selector' => '.button'
                    // ],
                    // [
                    //     'title' => 'Button Link',
                    //     'classes' => 'button--link',
                    //     'selector' => '.button'
                    // ]
                ]
            ],
            [
                'title' => 'Icon Lists',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Check Circle',
                        'classes' => 'iconList iconList--checkCircle',
                        'selector' => 'ul,ol'
                    ]
                ]
            ]
        ],
        'toolbars' => [
            'default' => [
                [
                    'formatselect',
                    'styleselect',
                    'forecolor backcolor',
                    'bold',
                    'italic',
                    // 'strikethrough',
                    // 'blockquote',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
//                    'justifyfull',
//                    'justify',
                    'alignjustify',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'pastetext',
                    'removeformat',
                    '|',
                    'nonbreaking',
                    'charmap',
                    '|',
                    'undo',
                    'redo',
                    'fullscreen'
                ]
            ],
            'basic' => [
                [
                    'bold',
                    'italic',
                    'strikethrough',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'undo',
                    'redo',
                    'fullscreen'
                ]
            ]
        ],
        'entities' => [
            '160' => 'nbsp',
            '173' => 'shy'
        ],
        'entity_encoding' => 'named',
    ];
}
