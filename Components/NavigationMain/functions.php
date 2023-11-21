<?php

namespace Flynt\Components\NavigationMain;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=NavigationMain', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_main') ?? Timber::get_pages_menu();
    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ? get_theme_mod('custom_logo') : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];
    $data['logo_dark'] = [
        'src' => get_theme_mod('logo_dark') ? get_theme_mod('logo_dark') : Asset::requireUrl('assets/images/logo-dark.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});


add_action('init', function () {
    register_nav_menus([
        'navigation_main' => __('Navigation Main', 'flynt')
    ]);
});

add_action('customize_register', function ($wp_customize) {

    //adding setting for dark logo
    $wp_customize->add_setting('logo_dark', [
        'default'        => 'Dark Logo',
    ]);

    $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'logo_dark', [
        'label'             => "Set Dark Logo",
        'section'           => 'title_tagline',
        'settings'          => 'logo_dark',
    ]));
});

Options::addTranslatable('NavigationMain', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('Aria Label', 'flynt'),
                'name' => 'ariaLabel',
                'type' => 'text',
                'default_value' => __('Main', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
    ],
]);
