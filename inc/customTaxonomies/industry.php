<?php

/**
 * Custom taxonomy for Industry in Flynt theme.
 */

namespace Flynt\CustomTaxonomies;

add_action('init', function (): void {
    $labels = [
        'name'                       => _x('Industries', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Industry', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Industries', 'flynt'),
        'all_items'                  => __('All Industries', 'flynt'),
        'parent_item'                => __('Parent Industry', 'flynt'),
        'parent_item_colon'          => __('Parent Industry:', 'flynt'),
        'new_item_name'              => __('New Industry Name', 'flynt'),
        'add_new_item'               => __('Add New Industry', 'flynt'),
        'edit_item'                  => __('Edit Industry', 'flynt'),
        'update_item'                => __('Update Industry', 'flynt'),
        'view_item'                  => __('View Industry', 'flynt'),
        'search_items'               => __('Search Industries', 'flynt'),
        'not_found'                  => __('No Industries Found', 'flynt'),
        'no_terms'                   => __('No Industries', 'flynt'),
        'items_list'                 => __('Industries List', 'flynt'),
        'items_list_navigation'      => __('Industries List Navigation', 'flynt'),
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

    register_taxonomy('industry', ['project'], $args);
});
