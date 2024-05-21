<?php

namespace Flynt\CustomPostTypes;

add_action('init', function (): void {
    $labels = [
        'name'                  => _x('Fixed Blocks', 'Component Post Type', 'flynt'),
        'singular_name'         => _x('Fixed Block', 'Component Post Type', 'flynt'),
        'menu_name'             => _x('Fixed Blocks', 'Component Post Type', 'flynt'),
        'name_admin_bar'        => __('Fixed Blocks', 'flynt'),
        'archives'              => __('Fixed Block Archives', 'flynt'),
        'attributes'            => __('Fixed Block Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Fixed Block:', 'flynt'),
        'all_items'             => __('All Fixed Blocks', 'flynt'),
        'add_new_item'          => __('Add New Fixed Blocks', 'flynt'),
        'new_item'              => __('New Fixed Blocks', 'flynt'),
        'edit_item'             => __('Edit Fixed Blocks', 'flynt'),
        'update_item'           => __('Update Fixed Blocks', 'flynt'),
        'view_item'             => __('View Fixed Blocks', 'flynt'),
        'view_items'            => __('View Fixed Blocks', 'flynt'),
        'search_items'          => __('Search Fixed Blocks', 'flynt'),
        'not_found'             => __('No fixed blocks found', 'flynt'),
        'not_found_in_trash'    => __('No fixed blocks found in Trash', 'flynt'),
        'items_list'            => __('Fixed Blocks list', 'flynt'),
        'items_list_navigation' => __('Fixed Blocks list navigation', 'flynt'),
        'filter_items_list'     => __('Filter fixed blocks list', 'flynt'),
    ];

    $args = [
        'labels'                => $labels,
        'supports'              => ['title', 'revisions'],
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-block-default',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'capability_type'       => 'page',
        'rewrite'               => false
    ];

    register_post_type('reusable-components', $args);
});
