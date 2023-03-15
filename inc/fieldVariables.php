<?php

/**
 * Defines field variables to be used across multiple components.
 */

namespace Flynt\FieldVariables;

function getTheme($default = '')
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

function getRawSvg()
{
    return [
        'label' => __('Raw SVG', 'flynt'),
        'instructions' => sprintf(
            'Insert raw svg e. g. from <a ref="%1$s" target="_blank">%1$s</a>',
            'https://heroicons.com/'
        ),
        'name' => 'rawSvg',
        'type' => 'textarea',
        'required' => 1,
        'rows' => 1,
        'new_lines' => '',
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