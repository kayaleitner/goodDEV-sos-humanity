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
    add_editor_style(Asset::requireUrl('assets/tinymce.scss'));
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
            // Send it to textcolor_map as true js array
            $init['textcolor_map'] = json_encode($config['textcolor_map']);
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
            __('Heading 1', 'flynt') => 'h1',
            __('Heading 2', 'flynt') => 'h2',
            __('Heading 3', 'flynt') => 'h3',
            __('Heading 4', 'flynt') => 'h4',
            __('Heading 5', 'flynt') => 'h5',
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
                    [
                        'title' => __('Mono', 'flynt'),
                        'classes' => 'font-mono',
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
                        'classes' => 'button button--brand',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Accent', 'flynt'),
                        'classes' => 'button button--accent',
                        'selector' => 'a'
                    ],
                    [
                        'title' => __('Button Arrow Icon', 'flynt'),
                        'classes' => 'button button--icon',
                        'selector' => 'a'
                    ],
                ]
            ],
            [
                'title' => __('Horizontal Line', 'flynt'),
                'icon' => '',
                'items' => [
                    [
                        'title' => __('Dark Blue', 'flynt'),
                        'classes' => 'bg-textColor',
                        'selector' => 'hr'
                    ],
                    [
                        'title' => __('Light Blue', 'flynt'),
                        'classes' => 'bg-hoverColor',
                        'selector' => 'hr'
                    ],
                    [
                        'title' => __('Orange', 'flynt'),
                        'classes' => 'bg-tangerine',
                        'selector' => 'hr'
                    ],
                    [
                        'title' => __('Yellow', 'flynt'),
                        'classes' => 'bg-yellow',
                        'selector' => 'hr'
                    ],
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
                    // 'blockquote',
                    'forecolor',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'alignjustify',
                    '|',
                    'hr',
                    'charmap',
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
                    // 'blockquote',
                    'forecolor',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'alignjustify',
                    '|',
                    'hr',
                    'charmap',
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
        ],
        'entities' => [
            '160' => 'nbsp',
            '173' => 'shy'
        ],
        'entity_encoding' => 'named',
    ];
}
