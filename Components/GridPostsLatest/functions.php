<?php

namespace Flynt\Components\GridPostsLatest;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'post';

// Hydrate to initial render
add_filter('Flynt/addComponentData?name=GridPostsLatest', function ($data) {
    $data['postType'] = POST_TYPE;
    $data['taxonomies'] = $data['taxonomies'] ?? [];
    $data['category'] = get_category_string($data['taxonomies']);

    $data['items'] = get_filtered_posts($data['category']);

    $data['filterTabs'] = array_map(fn ($filter) => get_filter_data($filter, $data), $data['filters']);

    $data['postTypeArchiveLink'] = get_post_type_archive_link(POST_TYPE);
    $data['json'] = json_encode($data['category']);

    return $data;
});

// Fetch latest posts with filters
function get_filtered_posts($category, $count = 7)
{

    $args = [
        'post_status'         => 'publish',
        'post_type'           => POST_TYPE,
        'posts_per_page'      => $count,
        'ignore_sticky_posts' => 1,
        'meta_key'            => 'date',
        'orderby'             => 'meta_value',
        'order'               => 'DESC',
        'post__not_in'        => [get_the_ID()],
    ];

    if ($category) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => $category,
            ],
        ];
    }

    return Timber::get_posts($args);
}



// Get category string from taxonomies
function get_category_string($taxonomies)
{
    if (!is_object($taxonomies) || !isset($taxonomies->id)) {
        return '';
    }
    return $taxonomies->id . ',' . join(',', get_term_children($taxonomies->id, 'category'));
}

// Get filter data based on filter type
function get_filter_data($filter, $data)
{
    switch ($filter) {
        case 'childTerms':
            return [
                'type' => $filter,
                'terms' => is_object($data['taxonomies']) ? array_map(fn ($term) => [
                    'value' => $term,
                    'term' => get_term($term),
                ], get_term_children($data['taxonomies']->term_id, $data['taxonomies']->taxonomy)) : [],
            ];
        case 'years':
            return get_year_filter_data($data['category']);
        case 'time':
            return [
                'type' => $filter,
                'terms' => [
                    [
                        'value' => 'current',
                        'term' => pll__('Bevorstehende ') . ' ' . (is_object($data['taxonomies']) ? $data['taxonomies']->name : ''),
                    ],
                    [
                        'value' => 'past',
                        'term' => pll__('Vergangene ') . ' ' . (is_object($data['taxonomies']) ? $data['taxonomies']->name : ''),
                    ],
                ],
            ];
        default:
            return [];
    }
}

// Get years filter data
function get_year_filter_data($category)
{
    $postsArray = get_filtered_posts($category, -1);
    
    $years = array_unique(array_map(fn ($post) => date('Y', strtotime($post->date)), (array) $postsArray));
    rsort($years);

    return [
        'type' => 'years',
        'terms' => array_map(fn ($year) => [
            'value' => $year,
            'term'  => $year
        ], $years)
    ];
}

// AJAX handlers
add_action('wp_ajax_nopriv_get_posts', 'Flynt\Components\GridPostsLatest\get_posts');
add_action('wp_ajax_get_posts', 'Flynt\Components\GridPostsLatest\get_posts');

function get_posts()
{
    $context = Timber::get_context();
    $filters = json_decode(stripslashes($_POST['filters']));

    $context['posts'] = Timber::get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $_POST['count'],
        'offset'         => $_POST['offset'],
        'cat'       => strval($filters->childTerms) ?: $_POST['category'],
        'meta_query'     => get_meta_query($filters),
        'has_password'   => false
    ]);

    foreach ($context['posts'] as $item) {
        Timber::render('/Partials/_item.twig', ['item' => $item]);
    }

    wp_die();
}

// Get meta query based on filters
function get_meta_query($filters)
{
    return [
        'relation' => 'OR',
        [
            'relation' => 'AND',
            [
                'key'     => 'date',
                'value'   => '0',
                'compare' => '>',
            ],
            [
                'key'     => 'date',
                'value'   => date('Ymd'),
                'compare' => $filters->time === 'current' ? '>=' : ($filters->time === 'past' ? '<' : 'NOT LIKE'),
            ],
            [
                'key'     => 'date',
                'value'   => [$filters->years . '0000', ($filters->years ?: 9998) + 1 . '0000'],
                'compare' => $filters->years ? 'BETWEEN' : 'NOT IN',
            ],
        ]
    ];
}

// ACF Layout configuration
function getACFLayout()
{
    return [
        'name' => 'GridPostsLatest',
        'label' => 'Grid: Posts Latest',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'wysiwyg',
                'instructions' => __('Want to add a headline? Or just leave it empty.', 'flynt'),
            ],
            [
                'label' => __('Category', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
                'instructions' => __('Select a category or leave empty to show from all posts.', 'flynt'),
                'taxonomy' => 'category',
                'field_type' => 'select',
                'allow_null' => 1,
                'multiple' => 1,
                'return_format' => 'object'
            ],
            [
                'label' => 'Filter Tabs',
                'name' => 'filters',
                'type' => 'checkbox',
                'choices' => [
                    'years' => 'Years',
                    'time' => 'Current/Past',
                    'childTerms' => 'Child Terms',
                ],
                'layout' => 'horizontal',
                'return_format' => 'value',
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'sub_fields' => [
                    FieldVariables\getTheme(),
                    FieldVariables\getColorBrandBackground(),
                    FieldVariables\getColorBrandText(),
                    FieldVariables\getMaxWidthContainer(),
                    FieldVariables\getPaddingTopBottom(),
                    FieldVariables\getPaddingLeftRight(),
                    [
                        'label' => __('Display Date', 'flynt'),
                        'name' => 'displayDate',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                    ]
                ]
            ]
        ]
    ];
}

// Translatable options
Options::addTranslatable('GridPostsLatest', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            ['label' => __('All Posts', 'flynt'), 'name' => 'allPosts', 'type' => 'text', 'default_value' => 'See More Posts'],
            ['label' => __('Read More', 'flynt'), 'name' => 'readMore', 'type' => 'text', 'default_value' => 'Read More']
        ],
    ]
]);
