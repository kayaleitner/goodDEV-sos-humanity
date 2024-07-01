<?php

/**
 * Defines field variables to be used across multiple components.
 */

namespace Flynt\FieldVariables;

function getTheme($default = ''): array
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
            '' => __('(none)', 'flynt'),
            'cbe' => __('CrossBoundary Energy', 'flynt'),
            'cbg' => __('CrossBoundary Group', 'flynt'),
        ],
        'default_value' => $default,
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

function getColorBackground($default = '#ffffff')
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

function getColorText($default = '#000000')
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
