<?php

namespace Flynt\Components\DonationNavigationFooter;

use Flynt\Utils\Options;
use Timber\Timber;
use function Flynt\Components\DonationLanguageSwitch\null;

//add_action('init', function () {
//    register_nav_menus([
//        'navigation_footer' => __('Navigation Footer', 'flynt')
//    ]);
//});

add_filter('Flynt/addComponentData?name=DonationNavigationFooter', function ($data) {
  $data['maxLevel'] = 0;
  $data['menu'] = Timber::get_menu('navigation_footer');
  $data['languages'] = get_languages();

  $options = Options::getTranslatable('NavigationFooter') ?: [];

  $data = array_merge($data, $options);

  return $data;
});

function get_languages() {
  $languages = [];

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


        $languages[] = [
          'slug' => $slug,
          'code' => strtoupper($slugLower),
          'name' => $name,
          'current' => $current,
          'url' => $url,
        ];
      }
    }
  }

  return $languages;
}
