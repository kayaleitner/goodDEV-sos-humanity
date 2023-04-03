<?php

namespace Flynt\Components\BlockProjectBox;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Flynt\ComponentManager;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockProjectBox', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});

add_filter('Flynt/addComponentData?name=BlockProjectBox', function ($data) {
    $componentManager = ComponentManager::getInstance();
    $componentPathFull = $componentManager->getComponentDirPath('BlockProjectBox');
    $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

    if (!empty($data['boxMountingType'])) {
        $data['boxMountingType'] = array_map(function ($item) use ($componentPath) {
            $item['icon'] = Asset::getContents("{$componentPath}Assets/['boxMountingType']['value']}.svg");
            return $item;
        }, $data['boxMountingType']);
    }
    return $data;
});

Options::addTranslatable('BlockProjectBox', [
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
                'label' => __('Location', 'flynt'),
                'name' => 'boxLocation',
                'type' => 'text',
                'default_value' => __('Location', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Generation Size', 'flynt'),
                'name' => 'boxGenerationSize',
                'type' => 'text',
                'default_value' => __('Generation Size', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Customer Segment', 'flynt'),
                'name' => 'boxCustomerSegment',
                'type' => 'text',
                'default_value' => __('Customer Segment', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Mounting type', 'flynt'),
                'name' => 'boxMountingType',
                'type' => 'text',
                'default_value' => __('Mounting type', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Storage size', 'flynt'),
                'name' => 'boxStorageSize',
                'type' => 'text',
                'default_value' => __('Storage size', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Panel count', 'flynt'),
                'name' => 'boxPanelCount',
                'type' => 'text',
                'default_value' => __('Panel count', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Commission date', 'flynt'),
                'name' => 'boxCommissionDate',
                'type' => 'text',
                'default_value' => __('Commission date', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Partners', 'flynt'),
                'name' => 'boxPartners',
                'type' => 'text',
                'default_value' => __('Partners', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Estimated overall renewable energy contribution (%)', 'flynt'),
                'name' => 'boxReEnContr',
                'type' => 'text',
                'default_value' => __('Estimated overall renewable energy contribution (%)', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
        ],
    ],
]);
