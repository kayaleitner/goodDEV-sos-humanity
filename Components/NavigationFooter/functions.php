<?php

namespace Flynt\Components\NavigationFooter;

use Flynt\Utils\Options;
use Timber\Timber;
use Flynt\FieldVariables;
use Flynt\Shortcodes;

add_action('init', function () {
    register_nav_menus([
        'navigation_footer' => __('Navigation Footer', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationFooter', function ($data) {
    $data['maxLevel'] = 0;
    $data['menu'] = Timber::get_menu('navigation_footer');

    return $data;
});

Options::addTranslatable('NavigationFooter', [
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
        'media_upload' => 0,
        'delay' => 1,
        'toolbar' => 'full',
        'default_value' => '©&nbsp;[year] [sitetitle]'
    ],
    [
        'label' => __('Options', 'flynt'),
        'name' => 'optionsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    array_merge(FieldVariables\getMaxWidthContainer(), [
        'wrapper' => [
            'width' => 50
        ]
    ]),
    array_merge(FieldVariables\getPaddingTopBottom(), [
        'wrapper' => [
            'width' => 50
        ]
    ]),
    array_merge(FieldVariables\getPaddingLeftRight(), [
        'wrapper' => [
            'width' => 50
        ]
    ]),
]);
