<?php

namespace Flynt\Components\GridJobs;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'job';

// hydrate to initial render
add_filter('Flynt/addComponentData?name=GridJobs', function ($data) {
    $postType = POST_TYPE;
    $data['postType'] = $postType;
    $data['taxonomies'] = $data['taxonomies'] ?: [];
    $catString = '';

    if (!empty($data['taxonomies'])) {
        $catString = $data['taxonomies']->id . ',' . join(',', get_term_children($data['taxonomies']->id, 'category'));
    }

    $meta_query = [
        'relation' => 'AND',
        [
            'key' => 'positionOpen',
            'value' => '1',
            'compare' => '='
        ]
    ];

    $query_args = [
        'post_status' => 'publish',
        'post_type' => $postType,
        'posts_per_page' => 7,
        'ignore_sticky_posts' => 1,
        'jobCategory' => $catString,
        'orderby' => 'title',
        'order' => 'DESC',
        'post__not_in' => [get_the_ID()],
    ];

    if ($data['showOnlyOpenPositions']) {
        $query_args['meta_query'] = $meta_query;
    }

    $data['items'] = Timber::get_posts($query_args);

    // Get taxonomies of post type
    $data['taxonomies'] = get_terms([
        'taxonomy' => 'jobCategory',
        'hide_empty' => true,
    ]);

    // Populate items with taxonomies
    foreach ($data['items'] as $item) {
        $item->taxonomies = get_the_terms($item->ID, 'jobCategory');
    }

    $data['json'] = json_encode($catString);
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'GridJobs',
        'label' => 'Grid: Jobs',
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
                'name' => 'preContentHtml',
                'type' => 'text',
                'tabs' => 'visual,text',
                'delay' => 1,
                'instructions' => __('Want to add a headline?Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
            ],
            [
                'label' => __('Category', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
                'instructions' => __('Select a job category or leave empty to show from all jobs.', 'flynt'),
                'taxonomy' => 'jobCategory',
                'field_type' => 'select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object'
            ],
            [
                'label' => __('Show only open positions', 'flynt'),
                'name' => 'showOnlyOpenPositions',
                'type' => 'true_false',
                'message' => __('If checked, only open positions will be shown.', 'flynt'),
                'default_value' => 0,
                // 30% width
                'wrapper' => [
                    'width' => '30'
                ]
            ],
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
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                ]
            ],
        ]
    ];
}


Options::addTranslatable('GridJobs', [
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
                'label' => __('Job Details', 'flynt'),
                'name' => 'labelJobDetails',
                'type' => 'text',
                'default_value' => 'Erfahre Mehr',
                'required' => 1,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Stelle offen', 'flynt'),
                'name' => 'labelJobOpen',
                'type' => 'text',
                'default_value' => 'Offene Stelle',
                'required' => 1,
                'wrapper' => [
                    'width' => 50
                ],
            ]
        ],
    ],
]);

