<?php

/*** Remove Default Post Type ***/

function remove_default_post_type($args, $postType) {
    if ($postType === 'post') {
        $args['public']                = false;
        $args['show_ui']               = false;
        $args['show_in_menu']          = false;
        $args['show_in_admin_bar']     = false;
        $args['show_in_nav_menus']     = false;
        $args['can_export']            = false;
        $args['has_archive']           = false;
        $args['exclude_from_search']   = true;
        $args['publicly_queryable']    = false;
        $args['show_in_rest']          = false;
    }

    return $args;
}
add_filter('register_post_type_args', 'remove_default_post_type', 0, 2);

// add_filter('post_type_labels_post', 'post_rename_labels');

// /**
// * Rename default post type to news
// *
// * @param object $labels
// * @hooked post_type_labels_post
// * @return object $labels
// */
// function post_rename_labels($labels)
// {
//     # Labels
//     $labels->name = 'Insights';
//     $labels->singular_name = 'Insight';

//     # Menu
//     $labels->menu_name = 'Insights';
//     $labels->name_admin_bar = 'Insight';

//     return $labels;
// }
