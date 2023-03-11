<?php

namespace Flynt\Components\NavigationMain;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_main' => __('Navigation Main', 'flynt')
    ]);
});

add_action('customize_register', function ($wp_customize) {

    //adding setting for dark logo
    $wp_customize->add_setting('logo_dark', array(
        'default'        => 'Dark Logo',
    ));

    $wp_customize->add_control( new \WP_Customize_Image_Control( $wp_customize, 'logo_dark', array(
        'label'             => "Set Dark Logo",
        'section'           => 'title_tagline',
        'settings'          => 'logo_dark',    
    )));
    
});


add_filter('Flynt/addComponentData?name=NavigationMain', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_main') ?? Timber::get_pages_menu();
    $data['logo'] = [
        'src' => get_theme_mod('custom_header_logo') ? get_theme_mod('custom_header_logo') : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];
    $data['logo_dark'] = [
        'src' => get_theme_mod('logo_dark') ? get_theme_mod('logo_dark') : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});
