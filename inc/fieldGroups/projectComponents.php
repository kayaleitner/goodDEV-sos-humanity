<?php

use ACFComposer\ACFComposer;
use Flynt\Components;
use Flynt\ComponentManager;
use Timber\Timber;

// add_filter('Flynt/addComponentData?name=BlockProjectBox', function ($data) {
//     $componentManager = ComponentManager::getInstance();
//     $componentPathFull = $componentManager->getComponentDirPath('BlockProjectBox');
//     $componentPath = str_replace(trailingslashit(get_template_directory()), '', $componentPathFull);

//     if (!empty($data['boxCustomerSegment'])) {
//         $data['boxCustomerSegment'] = array_map(function ($item) use ($componentPath) {
//             $item['icon'] = Asset::getContents("{$componentPath}Assets/{$item['boxCustomerSegment']['value']}.svg");
//             return $item;
//         }, $data['boxCustomerSegment']);
//     }
//     return $data;
// });

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'projectMeta',
        'title' => 'Structured Data',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'introTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'intro',
                'type' => 'textarea',
                'wrapper' => [
                    'width' => 100
                ]
            ],
            [
                'label' => __('Coordinates', 'flynt'),
                'name' => 'coordinatesTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Coordinates', 'flynt'),
                'name' => 'coordinates',
                'type' => 'google_map',
                'center_lat' => '',
                'center_lng' => '',
                'zoom' => '',
                'wrapper' => [
                    'width' => 100
                ]
            ],
            [
                'label' => __('Box', 'flynt'),
                'name' => 'boxTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Location', 'flynt'),
                'name' => 'boxLocation',
                'type' => 'url',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Generation Size', 'flynt'),
                'name' => 'boxGenerationSize',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Customer Segment', 'flynt'),
                'name' => 'boxCustomerSegment',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 0,
                'return_format' => 'array',
                'choices' => [
                    'agriculture' => 'Agriculture',
                    'beverages' => 'Beverages',
                    'cement/concrete' => 'Cement/Concrete',
                    'chemical' => 'Chemical',
                    'consumer' => 'Consumer',
                    'appliances' => 'Appliances',
                    'energy' => 'Energy',
                    'food' => 'Food',
                    'forestry' => 'Forestry',
                    'hotel' => 'Hotel',
                    'humanitarian' => 'Humanitarian',
                    'it/software' => 'IT/Software',
                    'manufacturing' => 'Manufacturing',
                    'metal' => 'Metal',
                    'mining' => 'Mining',
                    'oil/petrochemical' => 'Oil/Petrochemical',
                    'paper' => 'Paper',
                    'quarries' => 'Quarries',
                    'realestate' => 'Real Estate',
                    'telecom' => 'Telecom'
                ],
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Mounting Type', 'flynt'),
                'name' => 'boxMountingType',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 0,
                'return_format' => 'array',
                'choices' => [
                    'roof' => 'Roof',
                    'ground' => 'Ground',
                    'carport' => 'Carport'
                ],
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Storage Size', 'flynt'),
                'name' => 'boxStorageSize',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Panel Count', 'flynt'),
                'name' => 'boxPanelCount',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Commission Date', 'flynt'),
                'name' => 'boxCommissionDate',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Partners', 'flynt'),
                'name' => 'boxPartners',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Estimated overall renewable energy contribution (%)', 'flynt'),
                'name' => 'boxReEnContr',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'projectComponents',
        'title' => __('Project Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'projectComponents',
                'label' => __('Project Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockPulloutQuote\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                    Components\ReusableComponent\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
                ],
            ],
        ],
    ]);
});
