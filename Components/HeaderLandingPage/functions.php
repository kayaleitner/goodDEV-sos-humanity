<?php

namespace Flynt\Components\HeaderLandingPage;

use Flynt\Utils\Asset;

add_filter('Flynt/addComponentData?name=HeaderLandingPage', function ($data) {
    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo')) : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

function getACFLayout(): array
{
    return [
        'name' => 'HeaderLandingPage',
        'label' => ' Header Landing Page',
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text',
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Subtitle', 'flynt'),
                'name' => 'subtitle',
                'type' => 'text',
                'wrapper' => ['width' => 50],
            ],
        ],
    ];
}

