<?php

namespace Flynt\Components\NavigationFooterLinklist;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\ComponentManager;
use Flynt\FieldVariables;
use Timber;

add_filter('Flynt/addComponentData?name=NavigationFooterLinklist', function ($data) {
    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('NavigationFooterLinklist');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

    $data['logo'] = [
        'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo')) : Asset::requireUrl("{$componentPath}Assets/logo.svg"),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

Options::addTranslatable('NavigationFooterLinklist', [
    [
        'label' => __('About', 'flynt'),
        'name' => 'aboutTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('About', 'flynt'),
        'name' => 'aboutHtml',
        'type' => 'wysiwyg',
        'delay' => 1,
        'media_upload' => 0,
    ],
    [
        'label' => __('Contact', 'flynt'),
        'name' => 'contactTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Title', 'flynt'),
        'name' => 'contactPreContent',
        'type' => 'text',
    ],
    [
        'label' => __('Content', 'flynt'),
        'name' => 'contactContentHtml',
        'type' => 'wysiwyg',
        'delay' => 1,
        'media_upload' => 0,
    ],
    [
        'label' => __('Donations', 'flynt'),
        'name' => 'donationsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Title', 'flynt'),
        'name' => 'donationsPreContent',
        'type' => 'text',
    ],
    [
        'label' => __('Content', 'flynt'),
        'name' => 'donationsContentHtml',
        'type' => 'wysiwyg',
        'delay' => 1,
        'media_upload' => 0,
    ],
    [
      'label' => __('Organisation', 'flynt'),
      'name' => 'donationsOrganisation',
      'type' => 'text',
    ],
    [
      'label' => __('IBAN', 'flynt'),
      'name' => 'donationsIBAN',
      'type' => 'text',
    ],
    [
      'label' => __('BIC', 'flynt'),
      'name' => 'donationsBIC',
      'type' => 'text',
    ],
    [
        'label' => __('Partners', 'flynt'),
        'name' => 'partnersTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Title', 'flynt'),
        'name' => 'partnersPreContent',
        'type' => 'text',
    ],
    [
        'label' => __('Partners', 'flynt'),
        'name' => 'partnersPanels',
        'type' => 'repeater',
        'layout' => 'row',
        'min' => 1,
        'button_label' => __('Add Partner Logo', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('logo', 'flynt'),
                'name' => 'panelLogo',
                'type' => 'image',
                'preview_size' => 'small',
                'instructions' => __('Image-Format: PNG, SVG', 'flynt'),
                'required' => 1,
                'mime_types' => 'png,svg'
            ],
            [
                'label' => __('Link', 'flynt'),
                'name' => 'panelLink',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
            ]
        ],
    ],
    [
      'label' => __('Banking-App', 'flynt'),
      'name' => 'bankingAppTab',
      'type' => 'tab',
      'placement' => 'top',
      'endpoint' => 0
    ],
    [
      'label' => __('Title', 'flynt'),
      'name' => 'bankingAppPreContent',
      'type' => 'text',
    ],
    [
      'label' => __('Banking-App QR-Code', 'flynt'),
      'name' => 'bankingAppLogo',
      'type' => 'image',
      'return_format' => 'array',   // 'array' | 'id' | 'url' – je nach Bedarf
      'preview_size' => 'small',
      'instructions' => __('Image-Format: PNG, SVG', 'flynt'),
      'required' => 0,
      'mime_types' => 'png,svg',
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
