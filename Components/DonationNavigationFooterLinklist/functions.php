<?php

namespace Flynt\Components\DonationNavigationFooterLinklist;

use Flynt\ComponentManager;
use Flynt\Utils\Asset;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=DonationNavigationFooterLinklist', function ($data) {
  $componentManager = ComponentManager::getInstance();
  $componentPathFull = $componentManager->getComponentDirPath('DonationNavigationFooterLinklist');
  $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

  $options = Options::getTranslatable('NavigationFooterLinklist') ?: [];

  $data['logo'] = [
    'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo')) : Asset::requireUrl("{$componentPath}Assets/logo.svg"),
    'alt' => get_bloginfo('name'),
  ];
  $data = array_merge($data, $options);

  return $data;
});
