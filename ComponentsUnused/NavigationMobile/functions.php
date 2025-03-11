<?php

namespace Flynt\Components\NavigationMobile;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_action('init', function (): void {
    register_nav_menus([
        'navigation_mobile' => __('Navigation Mobile', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationMobile', function (array $data): array {
    $data['menu'] = Timber::get_menu('navigation_mobile') ?? Timber::get_pages_menu();
        $data['logo'] = [
            'src' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
            'alt' => get_bloginfo('name')
        ];
        // ];
        $data['logo_secondary'] = [
            'src' => get_theme_mod('logo_secondary'),
            'alt' => get_bloginfo('name')
        ];

         // Get Site Icon (Favicon)
    $data['site_icon'] = [
        'src' => get_site_icon_url() ?: Asset::requireUrl('assets/icons/default-favicon.png'),
        'alt' => 'Site Icon'
    ];
    
        return $data;
    });

Options::addTranslatable('NavigationMobile', [
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
]);
