<?php

/**
 * Custom taxonomy for Services in Flynt theme.
 */

namespace Flynt\CustomTaxonomies;

add_action('init', function (): void {
    $labels = [
        'name'                       => _x('Services', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Service', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Services', 'flynt'),
        'all_items'                  => __('All Services', 'flynt'),
        'parent_item'                => __('Parent Service', 'flynt'),
        'parent_item_colon'          => __('Parent Service:', 'flynt'),
        'new_item_name'              => __('New Service Name', 'flynt'),
        'add_new_item'               => __('Add New Service', 'flynt'),
        'edit_item'                  => __('Edit Service', 'flynt'),
        'update_item'                => __('Update Service', 'flynt'),
        'view_item'                  => __('View Service', 'flynt'),
        'search_items'               => __('Search Services', 'flynt'),
        'not_found'                  => __('No Services Found', 'flynt'),
        'no_terms'                   => __('No Services', 'flynt'),
        'items_list'                 => __('Services List', 'flynt'),
        'items_list_navigation'      => __('Services List Navigation', 'flynt'),
    ];

    $args = [
        'labels'                     => $labels,
        'hierarchical'               => true,  // Non-hierarchical (like tags)
        'public'                     => true,
        'show_ui'                    => true,
        'show_in_menu'               => true,   // Visible in WordPress Admin menu
        'show_in_nav_menus'          => true,   // Visible in WordPress Navigation Menus
        'show_tagcloud'              => true,   // Available in Tag Cloud Widget
        'show_admin_column'          => true,
        'query_var'                  => true,
    ];

    register_taxonomy('services', ['project'], $args);
});
