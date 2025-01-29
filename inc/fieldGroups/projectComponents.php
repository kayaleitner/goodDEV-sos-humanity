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
                'label' => __('Project Info', 'flynt'),
                'name' => 'projectTab',
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
                'label' => __('Client Name', 'flynt'),
                'name' => 'client',
                'type' => 'text',
                'wrapper' => [
                    'width' => 33
                ],
            ],
            [
                'label' => __('Website URL', 'flynt'),
                'name' => 'websiteUrl',
                'type' => 'url',
                'wrapper' => [
                    'width' => 33
                ],
            ],
            [
                'label' => __('Longterm Maintenance', 'flynt'),
                'name' => 'maintenance',
                'type' => 'true_false',
                'wrapper' => [
                    'width' => 33
                ],
                'ui' => 1,
            ],
            
            [
                'label' => __('Features', 'flynt'),
                'name' => 'features',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Desgined by', 'flynt'),
                'name' => 'designedBy',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50
                ],
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
                'label' => __('Media Gallery', 'flynt'),
                'name' => 'galleryTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => '    ',
                'name' => 'gallery',
                'type' => 'repeater',
                'layout' => 'row',
                'sub_fields' => [

                    [
                        'label' => __('Type', 'flynt'),
                        'name' => 'type',
                        'type' => 'button_group',
                        'allow_null' => 0,
                        'default_value' => 'image',
                        'choices' => [
                            'image' => 'Image',
                            'video' => 'Video',
                        ]
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Format: JPG, PNG, SVG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png',
                        'required' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'image',
                                ],
                            ]
                        ],
                    ],
                    [
                        'label' => __('Video', 'flynt'),
                        'instructions' => __('Provide a poster image and various formats for best performance', 'flynt'),
                        'name' => 'video',
                        'type' => 'group',
                        'layout' => 'row',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'video',
                                ],
                            ]
                        ],
                        'sub_fields' => [
                            [
                                'label' => __('Aria-Label', 'flynt'),
                                'name' => 'label',
                                'type' => 'text',
                                'required' => 1,
                            ],
                            [
                                'label' => __('Poster image', 'flynt'),
                                'instructions' => __('Image-Format: JPG, PNG, WEBM (choose smallest size!)', 'flynt'),
                                'name' => 'posterImage',
                                'type' => 'image',
                                'preview_size' => 'medium',
                                'required' => 0,
                                'mime_types' => 'jpg,jpeg,png,webp'
                            ],
                            [
                                'label' => __('Items', 'flynt'),
                                'name' => 'videoFiles',
                                'type' => 'repeater',
                                'layout' => 'table',
                                'min' => 1,
                                'button_label' => __('Add video format', 'flynt'),
                                'instructions' => __('Provide video in h264 (mp4), h265 (mp4), vp8 (webm) and vp9 (webm). Sort ascending by size!', 'flynt'),
                                'sub_fields' => [
                                    [
                                        'label' => __('Video', 'flynt'),
                                        'name' => 'videoFile',
                                        'type' => 'file',
                                        'required' => 1,
                                        'mime_types' => 'mp4,webm',
                                    ],
                                    [
                                        'label' => __('Codec', 'flynt'),
                                        'name' => 'codec',
                                        'type' => 'select',
                                        'required' => 1,
                                        'choices' => [
                                            'avc1' => 'h264',
                                            'hvc1' =>'h265',
                                            'vp8' =>'vp8',
                                            'vp9' => 'vp9',
                                        ],
                                        'default_value' => 'avc1',
                                    ],
                                ],
                            ],
                        ],
                    ],
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