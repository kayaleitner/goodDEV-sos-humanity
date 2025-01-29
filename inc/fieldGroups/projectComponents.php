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
                ],
            ],
            [
                'label' => __('Website URL', 'flynt'),
                'name' => 'websiteUrl',
                'type' => 'url',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Longterm Maintenance', 'flynt'),
                'name' => 'maintenance',
                'type' => 'true_false',
                'wrapper' => [
                    'width' => 50
                ],
                'ui' => 1,
            ],
            [
                'label' => __('Agency Partner', 'flynt'),
                'name' => 'agencyPartner',
                'type' => 'group',
                'wrapper' => [
                    'width' => 50
                ],
                'layout' => 'row',
                'sub_fields' => [
                    [
                        'label' => __('Name', 'flynt'),
                        'name' => 'name',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Website', 'flynt'),
                        'name' => 'website',
                        'type' => 'url',
                    ],
                ],
            ],
            [
                'label' => __('Time Frame', 'flynt'),
                'name' => 'timeFrame',
                'type' => 'group',
                'wrapper' => [
                    'width' => 50
                ],
                'layout' => 'row',
                'sub_fields' => [
                    [
                        'label' => __('Project Start', 'flynt'),
                        'name' => 'projectStart',
                        'type' => 'date_picker',
                    ],
                    [
                        'label' => __('Website Launch', 'flynt'),
                        'name' => 'websiteLaunch',
                        'type' => 'date_picker',
                    ],
                ],
            ],
        [
            'label' => __('Features', 'flynt'),
            'name' => 'featuresTab',
            'type' => 'tab',
            'placement' => 'top',
            'endpoint' => 0,
        ],
        [
            'label' => __('Features', 'flynt'),
            'name' => 'features',
            'type' => 'repeater',
            'sub_fields' => [
                [
                    'label' => __('Title', 'flynt'),
                    'name' => 'title',
                    'type' => 'text',
                    'wrapper' => [
                        'width' => 50
                    ],
                ],
                [
                    'label' => __('Description', 'flynt'),
                    'name' => 'description',
                    'type' => 'textarea',
                    'wrapper' => [
                        'width' => 50
                    ],
                ],
                [
                    'label' => __('Image', 'flynt'),
                    'name' => 'image',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'wrapper' => [
                        'width' => 50
                    ],
                ],
            ],
            'min' => 0,
            'max' => 0,
            'layout' => 'row',
            'button_label' => __('Add Feature', 'flynt'),
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

    // ACFComposer::registerFieldGroup([
    //     'name' => 'projectComponents',
    //     'title' => __('Project Blocks', 'flynt'),
    //     'style' => 'seamless',
    //     'fields' => [
    //         [
    //             'name' => 'projectComponents',
    //             'label' => __('Project Blocks', 'flynt'),
    //             'type' => 'flexible_content',
    //             'button_label' => __('Add Block', 'flynt'),
    //             'layouts' => [
    //                 Components\BlockWysiwyg\getACFLayout(),
    //                 Components\BlockImageText\getACFLayout(),
    //                 Components\BlockCards\getACFLayout(),
    //                 Components\BlockCarousel\getACFLayout(),
    //                 Components\BlockStatistics\getACFLayout(),
    //                 Components\BlockListingAuto\getACFLayout(),
    //             ],
    //         ],
    //     ],
    //     'location' => [
    //         [
    //             [
    //                 'param' => 'post_type',
    //                 'operator' => '==',
    //                 'value' => 'project',
    //             ],
    //         ],
    //     ],
    // ]);
});

// Hide Categories from Project post type in editor and admin menu
// add_action('admin_menu', function () {
//     // Remove Categories meta box from the Project editor
//     remove_meta_box('categorydiv', 'project', 'side');
    
//     // Remove Categories from the Project in the admin menu
//     remove_submenu_page('edit.php?post_type=project', 'edit-tags.php?taxonomy=category&post_type=project');
// });