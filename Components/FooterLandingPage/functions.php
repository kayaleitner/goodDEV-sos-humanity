<?php

namespace Flynt\Components\FooterLandingPage;

use Flynt\Utils\Asset;

add_filter('Flynt/addComponentData?name=FooterLandingPage', function ($data) {
    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo')) : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

