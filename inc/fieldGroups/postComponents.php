<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'projectSidebar',
        'title' => 'Sidebar',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('External Resource', 'flynt'),
                'instructions' => __('Use this link to link an external resource.', 'flynt'),
                'name' => 'externalResourceLink',
                'type' => 'link',
                'return_format' => 'array'
            ],
            [
                'label' => __('Facts', 'flynt'),
                'name' => 'factsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Fact Box Title', 'flynt'),
                'name' => 'factBoxTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Facts', 'flynt'),
                'name' => 'facts',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Fact', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    [
                        'label' => __('Fact', 'flynt'),
                        'name' => 'factTextarea',
                        'type' => 'textarea',
                        'maxlength' => 180
                    ]
                ]
            ],
            [
                'label' => __('Download', 'flynt'),
                'name' => 'downloadTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Download Link Label', 'flynt'),
                'name' => 'downloadLinkLabel',
                'type' => 'text',
                'wrapper' =>  [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Download Link', 'flynt'),
                'name' => 'download',
                'type' => 'file',
                'return_format' => 'array',
                'wrapper' =>  [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Authors', 'flynt'),
                'name' => 'authorsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Authors Section Title', 'flynt'),
                'name' => 'authorsBoxTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Author (part of CB team)', 'flynt'),
                'name' => 'internalAuthorz',
                'instructions' => __('Use this field to add authors that are part of the CB team.', 'flynt'),
                'type' => 'relationship',
                'post_type' => [
                    'people'
                ],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'post_object',
                'ui' => 1,
                'required' => 0,
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Authors (not part of CB team)', 'flynt'),
                'name' => 'authorz',
                'instructions' => __('Use this field to add authors that are external to the CB team.', 'flynt'),
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Author', 'flynt'),
                'min' => 1,
                'sub_fields' => [
                    // [
                    //     'label' => __('Internal Author', 'flynt'),
                    //     'name' => 'internalAuthor',
                    //     'type' => 'post_object',
                    //     'post_type' => [
                    //         'people'
                    //     ],
                    //     'allow_null' => 0,
                    //     'multiple' => 0,
                    //     'return_format' => 'post_object',
                    //     'ui' => 1,
                    //     'required' => 0,
                    //     'wrapper' => [
                    //         'width' => '100',
                    //     ],
                    // ],
                    [
                        'label' => __('Profile Picture', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                        'name' => 'authorImage',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png',
                        'wrapper' => [
                            'width' => 25
                        ],
                    ],
                    [
                        'label' => __('Name', 'flynt'),
                        'name' => 'authorName',
                        'type' => 'text',
                        'wrapper' =>  [
                            'width' => 25,
                        ]
                    ],
                    [
                        'label' => __('Position', 'flynt'),
                        'name' => 'authorPosition',
                        'type' => 'text',
                        'wrapper' =>  [
                            'width' => 25,
                        ]
                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'authorLink',
                        'type' => 'url',
                        'wrapper' =>  [
                            'width' => 25,
                        ]
                    ]
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'postComponents',
        'title' => __('Post Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'postComponents',
                'label' => __('Post Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockAuthorSelector\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\ListingProjects\getACFLayout(),
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
                    'value' => 'post',
                ],
            ],
        ],
    ]);
});
