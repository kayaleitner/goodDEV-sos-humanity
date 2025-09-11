<?php

namespace Flynt\Components\HeroLandingPage;

use Flynt\FieldVariables;

function getACFLayout(): array
{
    return [
        'name' => 'HeroLandingPage',
        'label' => 'Hero LandingPage',
        'sub_fields' => [
            [
                'label' => __('Images', 'flynt'),
                'name' => 'accordionImages',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Images', 'flynt'),
                'type' => 'group',
                'name' => 'images',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'label' => __('Desktop Image', 'flynt'),
                        'name' => 'imageDesktop',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'mime_types' => 'jpg,jpeg,png',
                        'required' => 1,
                        'instructions' => __('Image-Format: JPG, PNG. Recommended resolution greater than 2048 x 800 px.', 'flynt'),
                    ],
                    [
                        'label' => __('Mobile Image', 'flynt'),
                        'name' => 'imageMobile',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'mime_types' => 'jpg,jpeg,png',
                        'required' => 1,
                        'instructions' => __('Image-Format: JPG, PNG. Recommended resolution greater than 750 x 800 px.', 'flynt'),
                    ]
                ]
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'accordionContent',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Text', 'flynt'),
                'name' => 'text',
                'type' => 'wysiwyg',
                'instructions' => __('Optional text displayed below the image with brand color options.', 'flynt'),
                'media_upload' => 0,
                'tabs' => 'visual',
                'delay' => 0,
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getPaddingTopBottom(),
                    FieldVariables\getPaddingLeftRight(),
                ]
            ],
            [
                'label' => __('Copyright', 'flynt'),
                'name' => 'copyright',
                'type' => 'text',
                'instructions' => __('Optional copyright text shown over the image similar to HeroImageText.', 'flynt'),
            ],
        ]
    ];
}

// Provide helper data for template (image urls and srcsets)
add_filter('Flynt/addComponentData?name=HeroLandingPage', function ($data) {
    $images = $data['images'] ?? [];

    $normalizeToId = function ($val) {
        if (is_array($val)) {
            // ACF image array can have 'ID' or 'id'
            return $val['ID'] ?? $val['id'] ?? null;
        }
        if (is_int($val)) {
            return $val;
        }
        if (is_object($val)) {
            if (isset($val->id) && is_int($val->id)) {
                return $val->id;
            }
            if ($val instanceof \WP_Post) {
                return $val->ID;
            }
        }
        return null;
    };

    $desktop = $normalizeToId($images['imageDesktop'] ?? null);
    $mobile  = $normalizeToId($images['imageMobile'] ?? null);

    $data['desktop'] = $desktop ? [
        'src' => wp_get_attachment_image_url($desktop, 'full'),
        'srcset' => wp_get_attachment_image_srcset($desktop, 'full'),
        'alt' => get_post_meta($desktop, '_wp_attachment_image_alt', true) ?: ''
    ] : null;

    $data['mobile'] = $mobile ? [
        'src' => wp_get_attachment_image_url($mobile, 'full'),
        'srcset' => wp_get_attachment_image_srcset($mobile, 'full'),
        'alt' => get_post_meta($mobile, '_wp_attachment_image_alt', true) ?: ''
    ] : null;

    return $data;
});
