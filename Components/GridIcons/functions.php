<?php

namespace Flynt\Components\GridIcons;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=GridIcons', function ($data) {

    if (!empty($data['items'])) {
        $data['items'] = array_map(function ($item) {
            if (isset($item['image']->post_mime_type) && $item['image']->post_mime_type === 'image/svg+xml') {
                $path = get_attached_file($item['image']->ID);
                $item['image']->svg = file_get_contents($path);
            }
            return $item;
        }, $data['items']);
    }
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'GridIcons',
        'label' => 'Grid: Icons',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContent',
                'type' => 'text',
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Logos', 'flynt'),
                'name' => 'logosAccordion',
                'type' => 'accordion',
                'open' => 0,
                'multi_expand' => 0,
                'endpoint' => 0,
            ],
            [
                'label' => __('Icons', 'flynt'),
                'name' => 'iconPanels',
                'type' => 'repeater',
                'collapsed' => '',
                'min' => 1,
                'max' => 8,
                'layout' => 'block',
                'button_label' => 'Add',
                'sub_fields' => [
                    [
                        'label' => __('Source', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'small',
                        'library' => 'all',
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'instructions' => __('Image-Format: JPG, PNG, SVG. Recommended Minimum Width: 384px.', 'flynt'),
                        'required' => 1,
                        'wrapper' =>  [
                            'width' => '30',
                        ]
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'content',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' =>  [
                            'width' => '70',
                        ]
                    ]
                ]
            ],
            [
                'label' => '',
                'name' => 'logosAccordionEnd',
                'type' => 'accordion',
                'open' => 0,
                'multi_expand' => 0,
                'endpoint' => 1,
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getFadeIn(),
                    FieldVariables\getMoveIn(),
                    [
                        'label' => __('Show as Card', 'flynt'),
                        'name' => 'card',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                    ],
                ]
            ],
        ]
    ];
}
