<?php

/**
 * Custom taxonomy for Platform in Flynt theme.
 */

namespace Flynt\CustomTaxonomies;

add_action('init', function (): void {
    $labels = [
        'name'                       => _x('Platforms', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Platform', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Platforms', 'flynt'),
        'all_items'                  => __('All Platforms', 'flynt'),
        'parent_item'                => __('Parent Platform', 'flynt'),
        'parent_item_colon'          => __('Parent Platform:', 'flynt'),
        'new_item_name'              => __('New Platform Name', 'flynt'),
        'add_new_item'               => __('Add New Platform', 'flynt'),
        'edit_item'                  => __('Edit Platform', 'flynt'),
        'update_item'                => __('Update Platform', 'flynt'),
        'view_item'                  => __('View Platform', 'flynt'),
        'search_items'               => __('Search Platforms', 'flynt'),
        'not_found'                  => __('No Platforms Found', 'flynt'),
        'no_terms'                   => __('No Platforms', 'flynt'),
        'items_list'                 => __('Platforms List', 'flynt'),
        'items_list_navigation'      => __('Platforms List Navigation', 'flynt'),
    ];

    $args = [
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    ];

    register_taxonomy('platform', ['project'], $args);
});
