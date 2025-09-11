<?php

/**
 * Defines field variables to be used across multiple components.
 */

namespace Flynt\FieldVariables;
use function Flynt\Components\Grid\gridCol;


function getTheme()
{
    return [
        'label' => __('Theme', 'flynt'),
        'name' => 'theme',
        'type' => 'select',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'choices' => [
            '' => __('Base', 'flynt'),
            'themeLight' => __('Light', 'flynt'),
            'themeDark' => __('Dark', 'flynt'),
            'themeBrand' => __('Brand', 'flynt'),
            'themeSecondary' => __('Secondary', 'flynt'),
        ],
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getColorBrandBackground()
{
    return [
        'label' => __('Brand Color - Background', 'flynt'),
        'name' => 'colorBrandBackground',
        'type' => 'color_picker',
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getColorBrandText()
{
    return [
        'label' => __('Brand Color - Text', 'flynt'),
        'name' => 'colorBrandText',
        'type' => 'color_picker',
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getPaddingTopBottom($default = 'var(--padding_small)')
{
    return [
        'label' => __('Padding Top/Bottom', 'flynt'),
        'name' => 'paddingTopBottom',
        'type' => 'select',
        'instructions' => '',
        'choices' => array(
            'var(--padding_none)' => 'None',
            'var(--padding_xsmall)' => 'Extra Small',
            'var(--padding_small)' => 'Small',
            'var(--padding_medium)' => 'Medium',
            'var(--padding_large)' => 'Large',
            'var(--padding_xlarge)' => 'Extra Large'
        ),
        'default_value' => $default,
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 1,
        'ajax' => 0,
        'return_format' => 'value',
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getPaddingLeftRight()
{
    return [
        'label' => __('Padding Left/Right', 'flynt'),
        'name' => 'paddingLeftRight',
        'type' => 'select',
        'instructions' => '',
        'choices' => array(
            'var(--padding_none)' => 'None',
            'var(--padding_xsmall)' => 'Extra Small',
            'var(--padding_small)' => 'Small',
            'var(--padding_medium)' => 'Medium',
            'var(--padding_large)' => 'Large',
            'var(--padding_xlarge)' => 'Extra Large'
        ),
        'default_value' => 'var(--padding_small)',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 1,
        'ajax' => 0,
        'return_format' => 'value',
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getMaxWidthContainer()
{
    return [
        'label' => __('Container Max Width', 'flynt'),
        'name' => 'containerMaxWidth',
        'type' => 'select',
        'instructions' => '',
        'choices' => array(
            'var(--maxWidthContainer_full)' => 'Full',
            'var(--maxWidthContainer_wide)' => 'Wide',
            'var(--maxWidthContainer_narrow)' => 'Narrow'
        ),
        'default_value' => 'var(--maxWidthContainer_wide)',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 1,
        'ajax' => 0,
        'return_format' => 'value',
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getFadeIn()
{
    return [
        'label' => __('Fade In', 'flynt'),
        'name' => 'fadeIn',
        'type' => 'true_false',
        'instructions' => '',
        'default_value' => '1',
        'ui' => 1,
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getMoveIn()
{
    return [
        'label' => __('Move In', 'flynt'),
        'name' => 'moveIn',
        'type' => 'true_false',
        'instructions' => '',
        'default_value' => '1',
        'ui' => 1,
        'wrapper' => [
            'width' => '33',
        ],
    ];
}

function getIcon()
{
    return [
        'label' => __('Icon', 'flynt'),
        'name' => 'icon',
        'type' => 'text',
        'instructions' => __('Enter a valid icon name from <a href="https://feathericons.com">Feather Icons</a> (e.g. `check-circle`).', 'flynt'),
        'required' => 1
    ];
}


function getSize($default = 'medium'): array
{
    return [
        'label' => __('Size', 'flynt'),
        'name' => 'size',
        'type' => 'radio',
        'other_choice' => 0,
        'save_other_choice' => 0,
        'layout' => 'horizontal',
        'choices' => [
            'medium' => __('Medium', 'flynt'),
            'max' => __('Wide', 'flynt'),
            'full' => __('Full', 'flynt'),
        ],
        'default_value' => $default
    ];
}

function getAlignment($args = []): array
{
    $options = wp_parse_args($args, [
        'label' => __('Align', 'flynt'),
        'name' => 'align',
        'default' => 'center',
    ]);

    return [
        'label' => $options['label'],
        'name' => $options['name'],
        'type' => 'radio',
        'other_choice' => 0,
        'save_other_choice' => 0,
        'layout' => 'horizontal',
        'choices' => [
            'left' => __('Left', 'flynt'),
            'center' => __('Center', 'flynt'),
        ],
        'default_value' => $options['default']
    ];
}

function getTextAlignment($args = []): array
{
    $options = wp_parse_args($args, [
        'label' => __('Align text', 'flynt'),
        'name' => 'textAlign',
        'default' => 'left',
    ]);

    return [
        'label' => $options['label'],
        'name' => $options['name'],
        'type' => 'button_group',
        'choices' => [
            'left' => sprintf('<i class="dashicons dashicons-editor-alignleft" title="%1$s"></i>', __('Align text left', 'flynt')),
            'center' => sprintf('<i class="dashicons dashicons-editor-aligncenter" title="%1$s"></i>', __('Align text center', 'flynt'))
        ],
        'default_value' => $options['default']
    ];
}

function getNavStyle($default = 'light-blur')
{
    return [
        'label' => __('Navbar Style', 'flynt'),
        'name' => 'navStyle',
        'type' => 'select',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'choices' => [
            'light-blur' => __('Light with Blur', 'flynt'),
            'dark-blur' => __('Dark with Blur', 'flynt'),
            'light-clear' => __('Light Clear', 'flynt'),
            'dark-clear' => __('Dark Clear', 'flynt'),
        ],
        'default_value' => $default,
    ];
}

function getColorBackground($default = 'var(--bgColorDefault)')
{
    return [
        'label' => __('Color Background', 'flynt'),
        'instructions' => __('Used for block background.', 'flynt'),
        'name' => 'colorBackground',
        'type' => 'color_picker',
        'wrapper' => [
            'width' => 100,
        ],
        'default_value' => $default,
    ];
}

function getColorSecondary()
{
    return [
        'label' => __('Color Secondary', 'flynt'),
        'instructions' => __('Generally used for Block Titles and borders (if present). If not selected the default fallback color depends on the Block design.', 'flynt'),
        'name' => 'colorSecondary',
        'type' => 'color_picker',
        'wrapper' => [
            'width' => 100,
        ],
    ];
}

function getColorText($default = 'var(--textColorDefault)')
{
    return [
        'label' => __('Color Text', 'flynt'),
        'instructions' => __('Used for texts except Block Titles. If not selected the default fallback color depends on the Block design.', 'flynt'),
        'name' => 'colorText',
        'type' => 'color_picker',
        'wrapper' => [
            'width' => 100,
        ],
        'default_value' => $default,
    ];
}

function getComponentID($default = '')
{
    return [
        'label' => __('Anchor Link', 'flynt'),
        'name' => 'componentId',
        'type' => 'text',
        'default_value' => $default,
        'wrapper' => [
            'width' => 30,
        ],
    ];
}

function getColumnLayout(
    array $mobile = [1, 4],
    array $tablet = [1, 8],
    array $desktop = [4, 9],
    array $max = [4, 9],
    string $name = 'cols',
    string $label = 'Columns'
) {
    return gridCol($name, $label, [
        ['label' => 'Mobile', 'cols' => 4, 'start' => $mobile[0], 'end' => $mobile[1]],
        ['label' => 'Tablet', 'cols' => 8, 'start' => $tablet[0], 'end' => $tablet[1]],
        ['label' => 'Desktop', 'cols' => 12, 'start' => $desktop[0], 'end' => $desktop[1]],
        ['label' => 'Wide', 'cols' => 12, 'start' => $max[0], 'end' => $max[1]]
    ]);
}


function mobileVisibility($default = 'hidden xs:block')
{
    return [
        'label' => __('Hide on Mobile?', 'flynt'),
        'instructions' => __('Choose if you want the block to be hidden on mobile or not.', 'flynt'),
        'name' => 'mobileVisibility',
        'type' => 'select',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'choices' => [
            'hidden xs:block' => __('Yes (hide on mobile, show on desktop)', 'flynt'),
            'block' => __('No (show on both mobile and desktop)', 'flynt'),
        ],
        'default_value' => $default,
    ];
}

function field(string $name, string $label, array $field = [])
{
    return array_merge([
        'name' => $name,
        'label' => $label,
    ], $field);
}

function fieldType(string $name, string $label, string $type, array $field = [])
{
    return field($name, $label, array_merge($field, [
        'type' => $type,
    ]));
}

function group(string $name, string $label, array $fields = [], array $groupField = [], int $width = 100)
{
    return fieldType($name, $label, 'group', array_merge(
        [
            'layout' => 'row',
        ],
        $groupField,
        [
            'sub_fields' => $fields,
        ],
        [
            'wrapper' => [
                'width' => $width,
            ],
        ]
    ));
}

function responsiveField(string $name, string $label, array $field, array $mediumField = [], array $largeField = [], array $sizes = null, array $groupField = [], array $extraMediumField = [], int $width = 100): array
{
    $sizes = $sizes ?? [ 'small', 'large', ];
    $fields = [
        'mobile' => array_merge($field, [
            'label' => 'Mobile',
            'name' => 'mobile',
        ]),
        'tablet' => array_merge($field, $mediumField, [
            'label' => 'Tablet',
            'name' => 'tablet',
        ]),
        'desktop' => array_merge($field, $extraMediumField, [
            'label' => 'Desktop',
            'name' => 'desktop',
        ]),
        'max' => array_merge($field, $largeField, [
            'label' => 'Wide',
            'name' => 'max',
        ]),
    ];

    return group(
        $name,
        $label,
        array_map(function(string $k) use($fields) {
            return $fields[$k];
        }, $sizes),
        $groupField,
        $width
    );
}

function getCarouselOptions(int $speed = 300, int $autoplay = 0, int $delay = 4000, int $loop = 1): array
{
    return [
        [
            'label' => __('Carousel Options', 'flynt'),
            'name' => 'carouselOptions',
            'type' => 'group',
            'layout' => 'block',
            'sub_fields' => [
                [
                    'label' => __('Transition Duration', 'flynt'),
                    'name' => 'speed',
                    'type' => 'number',
                    'instructions' => __('Duration of transition between slides (in ms).', 'flynt'),
                    'default_value' => $speed,
                    'wrapper' => [
                        'width' => 25,
                    ],
                ],
                [
                    'label' => __('Autoplay', 'flynt'),
                    'name' => 'autoplay',
                    'type' => 'true_false',
                    'wrapper' => [
                        'width' => 25,
                    ],
                    'default_value' => $autoplay,
                    'ui' => 1, // Enable UI toggle switch
                ],
                [
                    'label' => __('Delay', 'flynt'),
                    'name' => 'delay',
                    'type' => 'number',
                    'wrapper' => [
                        'width' => 25,
                    ],
                    'instructions' => __('Delay time in milliseconds for autoplay.', 'flynt'),
                    'default_value' => $delay,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'carouselOptions.autoplay',
                                'operator' => '==',
                                'value' => '1',
                            ],
                        ],
                    ],
                ],
                [
                    'label' => __('Loop', 'flynt'),
                    'name' => 'loop',
                    'type' => 'true_false',
                    'wrapper' => [
                        'width' => 25,
                    ],
                    'default_value' => $loop,
                    'ui' => 1, // Enable UI toggle switch
                ],
            ]
        ]

    ];
}

