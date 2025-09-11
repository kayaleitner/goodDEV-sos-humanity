<?php

namespace Flynt\Components\DonationLanguageSwitch;

/**
 * Prepare language data from Polylang for the template and attach local flag assets when available.
 */
add_filter('Flynt/addComponentData?name=DonationLanguageSwitch', function ($data) {
    $languages = [];

    // Base path for local assets
    $assetsBaseUrl = trailingslashit(get_template_directory_uri()) . 'Components/DonationLanguageSwitch/Assets/';
    $assetExists = function (string $filename): bool {
        $path = trailingslashit(get_template_directory()) . 'Components/DonationLanguageSwitch/Assets/' . $filename;
        return file_exists($path);
    };

    if (function_exists('pll_the_languages')) {
        // Raw array with all details per language
        $items = pll_the_languages(['raw' => 1, 'hide_if_empty' => 0]);
        if (is_array($items)) {
            foreach ($items as $item) {
                $slug = $item['slug'] ?? '';
                $name = $item['name'] ?? '';
                $current = !empty($item['current_lang']);
                $url = $item['url'] ?? '#';

                // Normalize slugs for mapping (e.g., 'de', 'en', 'it')
                $slugLower = strtolower($slug);

                // Try a local asset by standardized name: {slug}.svg
                $localFile = $slugLower . '.svg';
                $localFlag = $assetExists($localFile) ? ($assetsBaseUrl . $localFile) : null;

                // Fallbacks: map known variants (english-flag.svg -> en.svg, germany-flag.svg -> de.svg) without renaming files
                if ($localFlag === null) {
                    $aliasMap = [
                        'de' => 'de-flag.svg',
                        'en' => 'en-flag.svg',
                        'it' => 'it-flag.svg',
                    ];
                    if (isset($aliasMap[$slugLower]) && $assetExists($aliasMap[$slugLower])) {
                        $localFlag = $assetsBaseUrl . $aliasMap[$slugLower];
                    }
                }

                // Final flag: prefer local asset, then Polylang flag_url
                $flag = $localFlag ?: ($item['flag_url'] ?? null);

                $languages[] = [
                    'slug' => $slug,
                    'code' => strtoupper($slugLower),
                    'name' => $name,
                    'current' => $current,
                    'url' => $url,
                    'flag' => $flag,
                ];
            }
        }
    }

    $data['languages'] = $languages;
    return $data;
});

function getACFLayout(): array
{
    return [
        'name' => 'DonationLanguageSwitch',
        'label' => 'Donation Language Switch',
        'sub_fields' => []
    ];
}
