<?php

namespace Flynt\Components\NavigationBurger;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\ComponentManager;
use Flynt\FieldVariables;
use Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_burger' => __('Navigation Burger', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationBurger', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_burger');

    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('NavigationBurger');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ?  wp_get_attachment_image_url(get_theme_mod('custom_logo')) : Asset::requireUrl("{$componentPath}Assets/logo.svg"),
        'alt' => get_bloginfo('name')
    ];

    if (!empty($data['social'])) {
        $data['social'] = array_map(function ($item) use ($componentPath) {
            $item['icon'] = Asset::getContents("{$componentPath}Assets/{$item['platform']['value']}.svg");
            return $item;
        }, $data['social']);
    }
    return $data;
});

// add_filter('Flynt/addComponentData?name=NavigationBurger', function ($data) {
//     if (!empty($data['social'])) {
//         $data['social'] = array_map(function ($item) {
//             $componentManager = ComponentManager::getInstance();
//             $componentPathFull = $componentManager->getComponentDirPath('NavigationBurger');
//             $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);
//             $item['icon'] = Asset::getContents("{$componentPath}Assets/{$item['platform']['value']}.svg");
//             return $item;
//         }, $data['social']);
//     }

//     return $data;
// });

Options::addTranslatable('NavigationBurger', [
    [
        'label' => __('Options', 'flynt'),
        'name' => 'optionsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    array_merge(FieldVariables\getMaxWidthContainer(), [
        'wrapper' => [
            'width' => 100
        ]
    ]),
    [
        'label' => __('CTA', 'flynt'),
        'name' => 'ctaTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => 'CTA Link',
        'name' => 'ctaLink',
        'type' => 'link',
        'return_format' => 'url',
        'wrapper' => [
            'width' => 50
        ]
    ],
    [
        'label' => 'CTA Text',
        'name' => 'ctaText',
        'type' => 'text',
        'wrapper' => [
            'width' => 50
        ]
    ],
    [
        'label' => __('Social Media', 'flynt'),
        'name' => 'socialMediaTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Social Platform', 'flynt'),
        'type' => 'repeater',
        'name' => 'social',
        'layout' => 'table',
        'button_label' => __('Add Social Link', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Platform', 'flynt'),
                'name' => 'platform',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 0,
                'return_format' => 'array',
                'choices' => [
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'twitter' => 'Twitter',
                    'youtube' => 'Youtube',
                    'linkedin' => 'LinkedIn',
                    'spotify' => 'Spotify',
                    'soundcloud' => 'Soundcloud',
                    'vimeo' => 'Vimeo',
                ]
            ],
            [
                'label' => __('Link', 'flynt'),
                'name' => 'url',
                'type' => 'text',
                'required' => 1
            ]
        ]
    ],
]);
