<?php

namespace Flynt\Components\BlockInsightHeader;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockInsightHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});

Options::addTranslatable('BlockInsightHeader', [
    [
        'label' => __('Name', 'flynt'),
        'name' => 'contactName',
        'type' => 'text',
        'wrapper' => [
            'width' => 25,
        ]
    ],
    [
        'label' => __('Position', 'flynt'),
        'name' => 'contactPosition',
        'type' => 'text',
        'wrapper' => [
            'width' => 25,
        ]
    ],
    [
        'label' => __('Email', 'flynt'),
        'name' => 'contactEmail',
        'type' => 'text',
        'wrapper' => [
            'width' => 25,
        ]
    ],
    [
        'label' => __('Contact Button Label', 'flynt'),
        'name' => 'ContactButtonLabel',
        'type' => 'text',
        'default_value' => 'Contact',
        'wrapper' => [
            'width' => 25,
        ]
    ]
]);
