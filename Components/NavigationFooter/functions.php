<?php

namespace Flynt\Components\NavigationFooter;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\FieldVariables;
use Flynt\Shortcodes;
use Flynt\ComponentManager;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_footer' => __('Navigation Footer', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationFooter', function ($data) {
    $data['maxLevel'] = 0;
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
        'label' => __('Logo', 'flynt'),
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
        'label' => __('Address', 'flynt'),
        'name' => 'addressTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Address Column Title', 'flynt'),
        'name' => 'addressColumnTitle',
        'type' => 'text'
    ],
    [
        'label' => __('Address', 'flynt'),
        'name' => 'addressHtml',
        'type' => 'wysiwyg',
        'media_upload' => 0,
        'delay' => 1,
        'toolbar' => 'basic',
    ],
    [
        'label' => __('Email', 'flynt'),
        'name' => 'addressEmail',
        'type' => 'text',
        'wrapper' => [
            'width' => 50
        ],
    ],
    [
        'label' => __('Phone', 'flynt'),
        'name' => 'addressPhone',
        'type' => 'text',
        'wrapper' => [
            'width' => 50
        ],
    ],
    [
        'label' => __('Menus', 'flynt'),
        'name' => 'menusTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Menu', 'flynt'),
        'name' => 'repeaterOuter',
        'type' => 'repeater',
        'layout' => 'block',
        'min' => '1',
        'button_label' => __('Add Menu', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text'
            ],
            [
                'label' => __('Menu Items', 'flynt'),
                'name' => 'repeaterInner',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add Page Link', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Page Link', 'flynt'),
                        'name' => 'pageLink',
                        'type' => 'link',
                        'return_format' => 'array',
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                ]
            ],
        ]
    ],
    [
        'label' => __('Copyrights', 'flynt'),
        'name' => 'copyrightsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Copyrights', 'flynt'),
        'name' => 'copyrightsHtml',
        'type' => 'wysiwyg',
        'media_upload' => 0,
        'delay' => 1,
        'toolbar' => 'basic',
        'default_value' => '©&nbsp;[year] [sitetitle]'
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
                    'twitter' => 'Twitter'
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
        ],
    ],
    [
        'label' => __('Options', 'flynt'),
        'name' => 'optionsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    // FieldVariables\getTheme(),
    FieldVariables\getColorBackground(),
    FieldVariables\getColorText(),
]);
