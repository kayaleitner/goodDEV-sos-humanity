<?php

namespace Flynt\Components\NavigationFooter;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\FieldVariables;
// use Flynt\Shortcodes;
use Flynt\ComponentManager;
use Timber\Timber;

add_action('init', function (): void {
    register_nav_menus([
        'navigation_footer' => __('Navigation Footer', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationFooter', function (array $data): array {
    $data['menu'] = Timber::get_menu('navigation_footer') ?? Timber::get_pages_menu();

    return $data;
});

add_filter('Flynt/addComponentData?name=NavigationFooter', function ($data) {
    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('NavigationFooter');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

    if (!empty($data['social'])) {
        $data['social'] = array_map(function ($item) use ($componentPath) {
            $item['icon'] = Asset::getContents("{$componentPath}Assets/{$item['platform']['value']}.svg");
            return $item;
        }, $data['social']);
    }
    return $data;
});

Options::addTranslatable('NavigationFooter', [
    [
        'label' => __('Links', 'flynt'),
        'name' => 'linkTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Label Link Title', 'flynt'),
        'name' => 'linkTitle',
        'type' => 'text',
        'default_value' => __('Important Links', 'flynt')
    ],
    [
        'label' => __('Links', 'flynt'),
        'name' => 'links',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => __('Add Link', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Link', 'flynt'),
                'type' => 'link',
                'name' => 'link',
                'required' => 1
            ],
        ]
    ],
    [
        'label' => __('CTA', 'flynt'),
        'name' => 'cta',
        'type' => 'link'
    ],
    [
        'label' => __('Contact', 'flynt'),
        'name' => 'contactTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Label Link Title', 'flynt'),
        'name' => 'contactTitle',
        'type' => 'text',
        'default_value' => __('Contact', 'flynt')
    ],
    [
        'label' => __('Contact Info', 'flynt'),
        'name' => 'contactInfo',
        'type' => 'wysiwyg',
        'media_upload' => 0,
    ],
    [
        'label' => __('CTA', 'flynt'),
        'instructions' => __('If a form is opened, URL will be ignored', 'flynt'),
        'name' => 'contactCta',
        'type' => 'link',
        'wrapper' => [
            'width' => '50'
        ]
    ],
    [
        'label' => __('Form to open in Popup Modal', 'flynt'),
        'instructions' => __('Select a WPForms form.', 'flynt'),
        'name' => 'contactForm',
        'type' => 'post_object',
        'post_type' => ['wpforms'],
        'allow_null' => 1,
        'wrapper' => [
            'width' => '50'
        ]
    ],
    [
        'label' => __('Imagery', 'flynt'),
        'name' => 'logoTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Logo', 'flynt'),
        'name' => 'logoFooter',
        'type' => 'image',
        'preview_size' => 'medium',
        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
        'required' => 0,
        'mime_types' => 'jpg,jpeg,png,svg'
    ],
    [
        'label' => __('Triangle Image', 'flynt'),
        'name' => 'triangleImage',
        'type' => 'image',
        'preview_size' => 'medium',
        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
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
                'default_value' => __('Footer', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Copy Right', 'flynt'),
                'name' => 'copyRight',
                'type' => 'text',
                'default_value' => __('© 2024 - German Foreign Ministry | All rights reserved', 'flynt'),
            ]
        ],
    ],
]);
