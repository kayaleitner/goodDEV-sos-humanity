<?php

namespace Flynt\Components\NavigationMain;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=NavigationMain', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_main') ?? Timber::get_pages_menu();
    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : Asset::requireUrl('assets/logo/logoBlack.svg'),
        'alt' => get_bloginfo('name')
    ];
    // ];
    $data['logo_secondary'] = [
        'src' => get_theme_mod('logo_secondary') ? get_theme_mod('logo_secondary') : Asset::requireUrl('assets/logo/logoOrange.svg'),
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
    $wp_customize->add_setting('logo_secondary', [
        'default'        => 'Secondary Logo',
    ]);
 
    $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'logo_secondary', [
        'label'             => "Set Secondary Logo",
        'section'           => 'title_tagline',
        'settings'          => 'logo_secondary',
    ]));

    //adding setting for dark logo
    // $wp_customize->add_setting('logo_dark', [
    //     'default'        => 'Dark Logo',
    // ]);

    // $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'logo_dark', [
    //     'label'             => "Set Dark Logo",
    //     'section'           => 'title_tagline',
    //     'settings'          => 'logo_dark',
    // ]));
});

Options::addTranslatable('NavigationMain', [
    [
        'label' => __('Call to Action', 'flynt'),
        'name' => 'ctaTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('CTA Link', 'flynt'),
        'name' => 'ctaLink',
        'type' => 'link',
        'return_format' => 'array',
        'wrapper' =>  [
            'width' => '100',
        ]
    ],
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
