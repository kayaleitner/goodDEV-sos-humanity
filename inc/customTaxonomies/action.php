<?php

namespace Flynt\CustomTaxonomies;

function registerActionTaxonomy()
{
    $labels = [
        'name'                       => _x('Actions', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Action', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Actions', 'flynt'),
        'all_items'                  => __('All Actions', 'flynt'),
        'edit_item'                  => __('Edit Action', 'flynt'),
        'view_item'                  => __('View Action', 'flynt'),
        'update_item'                => __('Update Action', 'flynt'),
        'add_new_item'               => __('Add New Action', 'flynt'),
        'new_item_name'              => __('New Action Name', 'flynt'),
        'search_items'               => __('Search Actions', 'flynt'),
        'not_found'                  => __('No Actions found', 'flynt'),
        'no_terms'                   => __('No Actions', 'flynt'),
        'items_list'                 => __('Actions list', 'flynt'),
        'items_list_navigation'      => __('Actions list navigation', 'flynt'),
        'choose_from_most_used'      => __('Choose from the most used Actions', 'flynt'),
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
        'rewrite'                    => ['slug' => 'action'],
    ];

    register_taxonomy('action', ['project', 'post'], $args);
}

add_action('init', '\\Flynt\\CustomTaxonomies\\registerActionTaxonomy');