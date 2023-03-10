<?php

namespace Flynt\Components\BlockImageTextFull;

use Flynt\FieldVariables;
use Flynt\Utils\Asset;
use Flynt\ComponentManager;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockImageTextFull', function ($data) {
    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('BlockImageTextFull');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

    if (!empty($data['icon'])) {
        $data['icon'] = array_map(function ($item) use ($componentPath) {
            $item['icon'] = Asset::getContents("{$componentPath}Assets/{$item['label']['value']}.svg");
            return $item;
        }, $data['icon']);
    }
    return $data;
});


function getACFLayout()
{
    return [
        'name' => 'BlockImageTextFull',
        'label' => __('Block: Image/Text Full', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Image', 'flynt'),
                'name' => 'imageTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Image Position', 'flynt'),
                'name' => 'imagePosition',
                'type' => 'button_group',
                'choices' => [
                    'left' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Image on the left', 'flynt')),
                    'right' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Image on the right', 'flynt'))
                ],
                // 'wrapper' => [
                //     'width' => 50,
                // ]
            ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 1,
                'mime_types' => 'jpg,jpeg,png,svg'
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Background Color', 'flynt'),
                'name' => 'bgcolor',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'choices' => [
                    'var(--primary)' => __('Primary', 'flynt'),
                    'var(--secondary)' => __('Secondary', 'flynt')
                ],
                'return_format' => 'value',
                // 'wrapper' => [
                //     'width' => 50,
                // ]
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 1,
            ],
            [
                'label' => __('Icon', 'flynt'),
                'name' => 'iconTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Icon', 'flynt'),
                'name' => 'icon',
                'type' => 'repeater',
                'layout' => 'table',
                'max' => 1,
                'button_label' => __('Add Icon', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Label', 'flynt'),
                        'name' => 'label',
                        'type' => 'select',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 1,
                        'ajax' => 0,
                        'return_format' => 'array',
                        'choices' => [
                            'suitcase' => 'Suitcase',
                            'goal' => 'Goal',
                            'check' => 'Check'
                        ]
                    ]
                ]
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
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getTheme()
                ]
            ]
        ]
    ];
}
