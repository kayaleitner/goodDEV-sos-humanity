<?php

namespace Flynt\CustomPostTypes;

function registerJobsPostType()
{
    $labels = [
        'name'                  => _x('Jobs', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Job', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Jobs', 'flynt'),
        'name_admin_bar'        => __('Job', 'flynt'),
        'archives'              => __('Job Archives', 'flynt'),
        'attributes'            => __('Job Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Job:', 'flynt'),
        'all_items'             => __('All Jobs', 'flynt'),
        'add_new_item'          => __('Add New Job', 'flynt'),
        'add_new'               => __('Add New', 'flynt'),
        'new_item'              => __('New Job', 'flynt'),
        'edit_item'             => __('Edit Job', 'flynt'),
        'update_item'           => __('Update Job', 'flynt'),
        'view_item'             => __('View Job', 'flynt'),
        'view_items'            => __('View Jobs', 'flynt'),
        'search_items'          => __('Search Jobs', 'flynt'),
        'not_found'             => __('No Jobs found', 'flynt'),
        'not_found_in_trash'    => __('No Jobs found in Trash', 'flynt'),
        'featured_image'        => __('Job Image', 'flynt'),
        'set_featured_image'    => __('Set job image', 'flynt'),
        'remove_featured_image' => __('Remove job image', 'flynt'),
        'use_featured_image'    => __('Use as job image', 'flynt'),
        'insert_into_item'      => __('Insert into job', 'flynt'),
        'uploaded_to_this_item' => __('Uploaded to this job', 'flynt'),
        'items_list'            => __('Jobs list', 'flynt'),
        'items_list_navigation' => __('Jobs list navigation', 'flynt'),
        'filter_items_list'     => __('Filter jobs list', 'flynt'),
    ];

    $args = [
        'label'                 => __('Jobs', 'flynt'),
        'description'           => __('Job Listings', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title'],
        'public'                => false,  // Publicly hidden
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-building',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
    ];

    register_post_type('job', $args);
}

add_action('init', '\\Flynt\\CustomPostTypes\\registerJobsPostType');