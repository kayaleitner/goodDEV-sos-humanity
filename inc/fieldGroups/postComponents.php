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
                'name' => 'externalResourceTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
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
                'placementF' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Fact-box Title', 'flynt'),
                'instructions' => __('The fact-box will not show until a title is added.', 'flynt'),
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
                'min' => 0,
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
                'min' => 0,
                'sub_fields' => [
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
                    ],
                ]
            ],
            [
                'label' => __('Press Contact', 'flynt'),
                'name' => 'pressContactTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Display Press Contact', 'flynt'),
                'name' => 'displayPressContact',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => __('Yes', 'flynt'),
                'ui_off_text' => __('No', 'flynt'),
            ],
            [
                'label' => __('Related Project', 'flynt'),
                'name' => 'related_project',
                'type' => 'post_object',
                'post_type' => ['project'],  // Reference only the Project post type
                'return_format' => 'object',  // Returns the post object for easy access
                'instructions' => __('Select a related project for this post.', 'flynt'),
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('URL', 'flynt'),
                'name' => 'url',
                'type' => 'url',
                'instructions' => __('Enter the URL for the post.', 'flynt'),
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Excerpt', 'flynt'),
                'name' => 'excerpt',
                'type' => 'textarea',
                'instructions' => __('Enter the excerpt for the post.', 'flynt'),
                'wrapper' => [
                    'width' => '100',
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
    ACFComposer::registerFieldGroup([
        'name' => 'postComponents',
        'title' => __('Post Blocks', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'postComponents',
                'label' => __('Post Blocks', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Block', 'flynt'),
                'layouts' => [
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
