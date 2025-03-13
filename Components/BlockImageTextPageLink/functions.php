<?php

namespace Flynt\Components\BlockImageTextPageLink;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'page';

add_filter('Flynt/addComponentData?name=BlockImageTextPageLink', function ($data) {

    $postType = POST_TYPE;

    $data['items'] = Timber::get_posts($data[$postType]);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockImageTextPageLink',
        'label' => 'Block: Image Text Page Link',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContent',
                'type' => 'text',
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Image Position', 'flynt'),
                'name' => 'imagePosition',
                'type' => 'button_group',
                'choices' => [
                    'imageRight' => '<i class=\'dashicons dashicons-align-left\' title=\'Image on the left\'></i>',
                    'imageLeft' => '<i class=\'dashicons dashicons-align-right\' title=\'Image on the right\'></i>'
                ],
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Page', 'flynt'),
                'name' => 'page',
                'type' => 'post_object',
                'post_type' => [
                    'page'
                ],
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'post_object',
                'ui' => 1,
                'required' => 0,
                'wrapper' => [
                    'width' => '30',
                ],
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
                'wrapper' => [
                    'width' => '70',
                ],
            ],
            // [
            //     'label' => __('Read More', 'flynt'),
            //     'name' => 'readMoreText',
            //     'type' => 'text',
            //     'default_value' => 'Mehr lesen',
            //     'wrapper' => [
            //         'width' => '100',
            //     ],
            // ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getMoveIn(),
                    FieldVariables\getFadeIn(),
                ]
            ]
        ]
    ];
}
