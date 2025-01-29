<?php

/**
 * Custom taxonomy for Project Type in Flynt theme.
 */

namespace Flynt\CustomTaxonomies;

add_action('init', function (): void {
    $labels = [
        'name'                       => _x('Project Types', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Project Type', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Project Types', 'flynt'),
        'all_items'                  => __('All Project Types', 'flynt'),
        'parent_item'                => __('Parent Project Type', 'flynt'),
        'parent_item_colon'          => __('Parent Project Type:', 'flynt'),
        'new_item_name'              => __('New Project Type Name', 'flynt'),
        'add_new_item'               => __('Add New Project Type', 'flynt'),
        'edit_item'                  => __('Edit Project Type', 'flynt'),
        'update_item'                => __('Update Project Type', 'flynt'),
        'view_item'                  => __('View Project Type', 'flynt'),
        'search_items'               => __('Search Project Types', 'flynt'),
        'not_found'                  => __('No Project Types Found', 'flynt'),
        'no_terms'                   => __('No Project Types', 'flynt'),
        'items_list'                 => __('Project Types List', 'flynt'),
        'items_list_navigation'      => __('Project Types List Navigation', 'flynt'),
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

    register_taxonomy('project_type', ['project'], $args);
});
