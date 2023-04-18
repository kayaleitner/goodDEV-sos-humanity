<?php

/**
 * This is an example file showcasing how you can add custom post types to your Flynt theme.
 *
 * For a full list of parameters see https://developer.wordpress.org/reference/functions/register_post_type/ or use https://generatewp.com/post-type/ to generate the code for you.
 */

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
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Projects', 'flynt'),
        'add_new_item'          => __('Add New Project', 'flynt'),
        'add_new'               => __('Add New', 'flynt'),
        'new_item'              => __('New Project', 'flynt'),
        'edit_item'             => __('Edit Project', 'flynt'),
        'update_item'           => __('Update Project', 'flynt'),
        'view_item'             => __('View Project', 'flynt'),
        'view_items'            => __('View Projects', 'flynt'),
        'search_items'          => __('Search Project', 'flynt'),
        'not_found'             => __('Not found', 'flynt'),
        'not_found_in_trash'    => __('Not found in Trash', 'flynt'),
        'featured_image'        => __('Featured Image', 'flynt'),
        'set_featured_image'    => __('Set featured image', 'flynt'),
        'remove_featured_image' => __('Remove featured image', 'flynt'),
        'use_featured_image'    => __('Use as featured image', 'flynt'),
        'insert_into_item'      => __('Insert into item', 'flynt'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'flynt'),
        'items_list'            => __('Projects list', 'flynt'),
        'items_list_navigation' => __('Projects list navigation', 'flynt'),
        'filter_items_list'     => __('Filter projects list', 'flynt'),
    ];
    $args = [
        'label'                 => __('Project', 'flynt'),
        'description'           => __('Project Description', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'thumbnail', 'revisions'],
        'taxonomies'            => ['post_tag'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-admin-site-alt3',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    ];
    register_post_type('project', $args);
}

add_action('init', '\\Flynt\\CustomPostTypes\\registerProjectPostType');
