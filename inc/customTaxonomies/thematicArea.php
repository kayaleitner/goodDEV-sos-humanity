<?php

namespace Flynt\CustomTaxonomies;

function registerThematicAreaTaxonomy()
{
    $labels = [
        'name'                       => _x('Thematic Areas', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Thematic Area', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Thematic Areas', 'flynt'),
        'all_items'                  => __('All Thematic Areas', 'flynt'),
        'edit_item'                  => __('Edit Thematic Area', 'flynt'),
        'view_item'                  => __('View Thematic Area', 'flynt'),
        'update_item'                => __('Update Thematic Area', 'flynt'),
        'add_new_item'               => __('Add New Thematic Area', 'flynt'),
        'new_item_name'              => __('New Thematic Area Name', 'flynt'),
        'search_items'               => __('Search Thematic Areas', 'flynt'),
        'not_found'                  => __('No Thematic Areas found', 'flynt'),
        'no_terms'                   => __('No Thematic Areas', 'flynt'),
        'items_list'                 => __('Thematic Areas list', 'flynt'),
        'items_list_navigation'      => __('Thematic Areas list navigation', 'flynt'),
        'choose_from_most_used'      => __('Choose from the most used Thematic Areas', 'flynt'),
    ];

    $args = [
        'labels'                     => $labels,
        'hierarchical'               => true,  // Non-hierarchical like tags
        'public'                     => true,
        'show_ui'                    => true,
        'show_in_menu'               => true,   // Ensure it's true for the taxonomy to appear
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_admin_column'          => true,
        'query_var'                  => true,
        'rewrite'                    => ['slug' => 'thematic-area'],
    ];

    register_taxonomy('thematic_area', ['project', 'post'], $args);
}

add_action('init', '\\Flynt\\CustomTaxonomies\\registerThematicAreaTaxonomy');