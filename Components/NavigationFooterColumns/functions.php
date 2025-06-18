<?php

namespace Flynt\Components\NavigationFooterColumns;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\ComponentManager;
use Flynt\FieldVariables;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_footer_columns' => __('Navigation Footer Columns', 'flynt'),
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationFooterColumns', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_footer_columns');

    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('NavigationFooterColumns');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo')) : Asset::requireUrl("{$componentPath}Assets/logo.svg"),
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

function getACFLayout()
{
    return [
        'name' => 'navigationFooterColumns',
        'label' => 'Navigation: Footer Columns',
        'sub_fields' => [
            [
                'label' => '',
                'name' => 'message',
                'type' => 'message',
                'message' => "Add this component at the end of the page to expand footer.<br>Create columns from <a href=\"/wp/wp-admin/nav-menus.php\" target='_blank'>Menus</a> section and assign menu to \"Navigation Footer Columns\" location. Social settings are found in <a href=\"/wp/wp-admin/admin.php?page=TranslatableOptions-Default\" target='_blank'>Translatable Options</a>.<br> Alternatively, render the component in <b>document.twig</b> to have it always in fixed position throughout the website.",
                'new_lines' => 'wpautop',
            ]
        ]
    ];
}

Options::addTranslatable('NavigationFooterColumns', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Menu Title', 'flynt'),
        'name' => 'menuPreContent',
        'type' => 'text',
    ],
    [
        'label' => __('Social Title', 'flynt'),
        'name' => 'socialPreContent',
        'type' => 'text',
    ],
    [
        'label' => __('Newsletter Title', 'flynt'),
        'name' => 'newsletterPreContent',
        'type' => 'text',
    ],
    [
        'label' => __('Social Media', 'flynt'),
        'name' => 'socialmediaTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
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
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'mastodon' => 'Mastodon',
                    'twitter' => 'Twitter',
                    'youtube' => 'Youtube',
                    'linkedin' => 'LinkedIn',
                    'spotify' => 'Spotify',
                    'soundcloud' => 'Soundcloud',
                    'vimeo' => 'Vimeo',
                    'website' => 'Website',
                    'xing' => 'Xing',
                    'bluesky' => 'Bluesky',
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
        'label' => __('Newsletter', 'flynt'),
        'name' => 'newsletterTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Newsletter', 'flynt'),
        'name' => 'newsletter',
        'type' => 'wysiwyg',
        'delay' => 1,
        'media_upload' => 0,
    ],
    [
        'label' => __('Options', 'flynt'),
        'name' => 'optionsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],

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
