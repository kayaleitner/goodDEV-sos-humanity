<?php

namespace Flynt\Components\NavigationPreNav;

use Timber\Timber;
use Flynt\Utils\Options;

add_action('init', function () {
    register_nav_menus([
        'navigation_pre' => __('Pre Navigation', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationPreNav', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_pre');

    return $data;
});

Options::addTranslatable('NavigationPreNav', [
    [
        'label' => __('Left Aligned Text', 'flynt'),
        'name' => 'leftAlignedText',
        'type' => 'text',
    ],
    [
        'label' => __('Search Input Placeholder', 'flynt'),
        'name' => 'searchInputPlaceholder',
        'type' => 'text',
    ],
]);
