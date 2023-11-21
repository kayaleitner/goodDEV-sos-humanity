<?php

use Flynt\Utils\Options;

function parseColorPalette()
{
    $cssContent = file_get_contents(get_template_directory() . '/assets/styles/_colors.css');
    $parser = new \Sabberworm\CSS\Parser($cssContent);
    $cssDocument = $parser->parse();
    $colors = array();
    $theme = get_option('options_globalThemeOption');

    foreach ($cssDocument->getAllDeclarationBlocks() as $block) {
        foreach ($block->getSelectors() as $selector) {
            if ($selector->getSelector() === ':root' || $selector->getSelector() === "[data-theme='$theme']") {
                // exit(var_dump($block->getRules()));
                foreach ($block->getRules() as $rule) {
                    $outputFormat = \Sabberworm\CSS\OutputFormat::create();
                    $slug = str_replace('-', '', $rule->getRule());
                    if (str_contains($rule->getValue()->render($outputFormat), '#')) {
                        $colors[] = [
                            'name' => $slug,
                            'slug' => $slug,
                            'color' => $rule->getValue()->render(\Sabberworm\CSS\OutputFormat::create()),
                        ];
                    }
                }
            }
        }
    }

    return $colors;
}

add_theme_support('editor-color-palette', parseColorPalette());
