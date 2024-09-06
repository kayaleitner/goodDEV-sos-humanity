<?php

namespace Flynt\Components\BlockPartners;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockPartners', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockPartners',
        'label' => __('Partners: Logos', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Partner Logos', 'flynt'),
                'name' => 'partnerlogos',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __('Add Partner Logo', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Logo', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'instructions' => __('Image-Format: SVG.', 'flynt'),
                        'required' => 0,
                        'mime_types' => 'svg'
                    ],
                    [
                        'label' => __('Flex Basis', 'flynt'),
                        'name' => 'basis',
                        'type' => 'number',
                        'instructions' => __('Determines the width relative to the other logos.', 'flynt'),
                        'append' => 'px',
                        'default_value' => 0,
                        'required' => 0,
                    ],
                    [
                        'label' => __('Flex Basis (>=980px)', 'flynt'),
                        'name' => 'basisXxl',
                        'type' => 'number',
                        'instructions' => __('Determines the width relative to the other logos.', 'flynt'),
                        'append' => 'px',
                        'default_value' => 0,
                        'required' => 0,
                    ],
                ]
            ],
        ]
    ];
}
