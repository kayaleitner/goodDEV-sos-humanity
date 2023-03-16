<?php

namespace Flynt\Components\BlockTeamHeader;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockTeamHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});

Options::addTranslatable('BlockTeamHeader', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('Back Button', 'flynt'),
                'name' => 'backButton',
                'type' => 'text',
                'default_value' => __('Go back to Our Team', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Back Button Link', 'flynt'),
                'name' => 'backButtonLink',
                'type' => 'link',
                'required' => 1,
                'wrapper' => [
                    'width' => 50
                ],
            ]
        ],
    ],
]);
