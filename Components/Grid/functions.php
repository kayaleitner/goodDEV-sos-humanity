<?php

namespace Flynt\Components\Grid;

use function Flynt\FieldVariables\responsiveField;

function gridCol(string $name, string $label, array $field = [], array $tabletField = [], array $desktopField = [], array $wideField = [], array $sizes = ['small', 'large']): array
{
    return responsiveField(
        $name,
        $label,
        array_merge(
            [
                'default_value' => 6,
            ],
            $field,
            [
                'type' => 'select',
                'required' => 1,
                'allow_null' => 0,
                'multiple' => 0,
                'choices' => (
                    array_combine(
                        range(1, 6),
                        range(1, 6)
                    ) +
                    ($field['includeStart'] ?? false ? [ 'start' => 'Start', ] : []) +
                    ($field['includeEnd'] ?? false ? [ 'end' => 'End', ] : [])
                ),
            ]
        ),
        array_merge(
            $tabletField,
            [
                'type' => 'select',
                'required' => 1,
                'allow_null' => 0,
                'multiple' => 0,
                'choices' => array_combine(
                    range(1, 6),
                    range(1, 6)
                ),
            ]
        ),
        array_merge(
            $desktopField,
            [
                'type' => 'select',
                'required' => 1,
                'allow_null' => 0,
                'multiple' => 0,
                'choices' => array_combine(
                    range(1, 12),
                    range(1, 12)
                ),
            ]
        ),
        $sizes,
        [
            'instructions' => 'How many columns should the element span?',
        ],
        array_merge(
            $wideField,
            [
                'type' => 'select',
                'required' => 1,
                'allow_null' => 0,
                'multiple' => 0,
                'choices' => array_combine(
                    range(1, 12),
                    range(1, 12)
                ),
            ]
        ),
    );
}
