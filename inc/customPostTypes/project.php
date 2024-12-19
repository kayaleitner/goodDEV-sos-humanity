<?php

namespace Flynt\CustomPostTypes;

function registerProjectPostType()
{
    $labels = [
        'name'                  => _x('Projects', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Project', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Projects', 'flynt'),
        'name_admin_bar'        => __('Project', 'flynt'),
        'archives'              => __('Project Archives', 'flynt'),
        'attributes'            => __('Project Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Project:', 'flynt'),
        'all_items'             => __('All Projects', 'flynt'),
        'add_new_item'          => __('Add New Project', 'flynt'),
        'add_new'               => __('Add New', 'flynt'),
        'new_item'              => __('New Project', 'flynt'),
        'edit_item'             => __('Edit Project', 'flynt'),
        'update_item'           => __('Update Project', 'flynt'),
        'view_item'             => __('View Project', 'flynt'),
        'view_items'            => __('View Projects', 'flynt'),
        'search_items'          => __('Search Projects', 'flynt'),
        'not_found'             => __('No Projects found', 'flynt'),
        'not_found_in_trash'    => __('No Projects found in Trash', 'flynt'),
        'featured_image'        => __('Project Image', 'flynt'),
        'set_featured_image'    => __('Set project image', 'flynt'),
        'remove_featured_image' => __('Remove project image', 'flynt'),
        'use_featured_image'    => __('Use as project image', 'flynt'),
        'insert_into_item'      => __('Insert into project', 'flynt'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'flynt'),
        'items_list'            => __('Projects list', 'flynt'),
        'items_list_navigation' => __('Projects list navigation', 'flynt'),
        'filter_items_list'     => __('Filter projects list', 'flynt'),
    ];

    $args = [
        'label'                 => __('Projects', 'flynt'),
        'description'           => __('Projects Custom Post Type', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'excerpt', 'thumbnail', 'revisions'],
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    ];

    register_post_type('project', $args);
}

add_action('init', '\\Flynt\\CustomPostTypes\\registerProjectPostType');