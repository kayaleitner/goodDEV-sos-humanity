<?php

namespace Flynt\Components\NavigationBurger;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_burger' => __('Navigation Burger', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationBurger', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_burger') ?? Timber::get_pages_menu();

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

Options::addTranslatable('NavigationBurger', [
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
            [
                'label' => __('Toggle Menu', 'flynt'),
                'name' => 'toggleMenu',
                'type' => 'text',
                'default_value' => __('Toggle Menu', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
    ],
    [
        'label' => __('Social Media', 'flynt'),
        'name' => 'socialmediaTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Social Media Title', 'flynt'),
        'name' => 'socialMediaTitle',
        'type' => 'text'
    ],
    [
        'label' => __('Social Platform', 'flynt'),
        'name' => 'social',
        'type' => 'repeater',
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
                    'linkedin' => 'Linkedin',
                    'facebook' => 'Facebook',
                    'twitter' => 'Twitter',
                    'instagram' => 'Instagram'
                ]
            ],
            [
                'label' => __('Link', 'flynt'),
                'name' => 'url',
                'type' => 'url',
                'required' => 1
            ],
        ]
    ],
    [
        'label' => __('Image', 'flynt'),
        'name' => 'imageTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Image', 'flynt'),
        'name' => 'image',
        'type' => 'image',
        'preview_size' => 'medium',
        'instructions' => __('Image-Formats: PNG, SVG, JPG', 'flynt'),
    ]
]);
