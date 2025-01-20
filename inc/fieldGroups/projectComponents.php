<?php

use ACFComposer\ACFComposer;
use Flynt\Components;
use Flynt\ComponentManager;
use Timber\Timber;

// add_filter('Flynt/addComponentData?name=ElementProjectBox', function ($data) {
//     $componentManager = ComponentManager::getInstance();
//     $componentPathFull = $componentManager->getComponentDirPath('ElementProjectBox');
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
                'required' => 1,
                'zoom' => '',
                'wrapper' => [
                    'width' => 100
                ]
            ],
            [
                'label' => __('Data', 'flynt'),
                'name' => 'dataTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Location URL', 'flynt'),
                'name' => 'boxLocation',
                'type' => 'url',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Generation Size', 'flynt'),
                'name' => 'boxGenerationSize',
                'type' => 'number',
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
                // 'allow_custom' => 1,
                // 'layout' => 'horizontal',
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
                'type' => 'number',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Panel Count', 'flynt'),
                'name' => 'boxPanelCount',
                'type' => 'number',
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
            [
                'label' => __('Project Link', 'flynt'),
                'name' => 'projectLink',
                'type' => 'link',
                'instructions' => __('Enter the project URL.', 'flynt'),
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Info Box Text', 'flynt'),
                'name' => 'infoBoxTexts',
                'type' => 'repeater',
                'instructions' => __('Add title and text for each info box text.', 'flynt'),
                'button_label' => __('Add Info Box Text', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => '30',
                        ],
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'text',
                        'type' => 'textarea',
                        'rows' => 3,
                        'wrapper' => [
                            'width' => '70',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Show Related News', 'flynt'),
                'name' => 'showRelatedNews',
                'type' => 'true_false',
                'instructions' => __('Show related news on the bottom of the project page.', 'flynt'),
                'ui' => 1, // Enable UI toggle switch
                'default_value' => true,
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('File Download', 'flynt'),
                'name' => 'fileDownload',
                'type' => 'file',
                'instructions' => __('Upload a file for users to download.', 'flynt'),
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => '', // Leave empty for all file types
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Button Label', 'flynt'),
                'name' => 'buttonLabel',
                'type' => 'text',
                'instructions' => __('Enter the label for the download button.', 'flynt'),
                'wrapper' => [
                    'width' => '50',
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
    ACFComposer::registerFieldGroup([
        'name' => 'projectComponents',
        'title' => __('Project Blocks', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'projectComponents',
                'label' => __('Project Blocks', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Block', 'flynt'),
                'layouts' => [
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockCards\getACFLayout(),
                    Components\BlockCarousel\getACFLayout(),
                    Components\BlockStatistics\getACFLayout(),
                    Components\BlockListingAuto\getACFLayout(),
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

// Hide Categories from Project post type in editor and admin menu
// add_action('admin_menu', function () {
//     // Remove Categories meta box from the Project editor
//     remove_meta_box('categorydiv', 'project', 'side');
    
//     // Remove Categories from the Project in the admin menu
//     remove_submenu_page('edit.php?post_type=project', 'edit-tags.php?taxonomy=category&post_type=project');
// });