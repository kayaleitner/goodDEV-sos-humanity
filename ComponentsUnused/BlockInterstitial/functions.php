<?php

namespace Flynt\Components\BlockInterstitial;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

function getACFLayout()
{
    return [
        'name' => 'BlockInterstitial',
        'label' => __('Interstitial', 'flynt'),
        'sub_fields' => [
            [
                 [
                    'label' => __('Background Color', 'flynt'),
                    'name' => 'backgroundColor',
                    'type' => 'color_picker',
                 ],
                 [
                    'label' => __('Text', 'flynt'),
                    'name' => 'text',
                    'type' => 'textarea',
                 ],
            ],
        ]
    ];
}
