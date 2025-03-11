<?php

//* Remove Default Post Type ***/

// function remove_default_post_type($args, $postType) {
//     if ($postType === 'post') {
//         $args['public']                = false;
//         $args['show_ui']               = false;
//         $args['show_in_menu']          = false;
//         $args['show_in_admin_bar']     = false;
//         $args['show_in_nav_menus']     = false;
//         $args['can_export']            = false;
//         $args['has_archive']           = false;
//         $args['exclude_from_search']   = true;
//         $args['publicly_queryable']    = false;
//         $args['show_in_rest']          = false;
//     }

//     return $args;
// }

// add_filter('register_post_type_args', 'remove_default_post_type', 0, 2);

function edit_default_post_type($args, $postType) {
    if ($postType === 'post') {
        $args['public']                = false;
        $args['publicly_queryable']    = false;
        $args['show_ui']               = true;
        $args['show_in_menu']          = true;
        $args['show_in_admin_bar']     = true;
        $args['show_in_nav_menus']     = true;
        $args['can_export']            = false;
        $args['has_archive']           = false;
        $args['rewrite']               = false;
        $args['exclude_from_search']   = false;
        $args['show_in_rest']          = false;
    }

    return $args;
}

add_filter('register_post_type_args', 'edit_default_post_type', 0, 2);


/**
* Rename default post type to news
*
* @param object $labels
* @hooked post_type_labels_post
* @return object $labels
*/
function post_rename_labels($labels)
{
    // Set the new name here
    $new_name = 'News';
    $singular_name = 'Article';

    // Labels
    $labels->name                  = $new_name;
    $labels->singular_name         = $new_name;
    $labels->add_new               = 'Add New ' . $singular_name;
    $labels->add_new_item          = 'Add New ' . $singular_name;
    $labels->edit_item             = 'Edit ' . $singular_name;
    $labels->new_item              = 'New ' . $singular_name;
    $labels->view_item             = 'View ' . $singular_name;
    $labels->view_items            = 'View ' . $new_name; // . 's';
    $labels->search_items          = 'Search ' . $new_name; // . 's';
    $labels->not_found             = 'No ' . $new_name . ' found';
    $labels->not_found_in_trash    = 'No ' . $new_name . ' found in Trash';
    $labels->parent_item_colon     = 'Parent ' . $new_name . ':';
    $labels->all_items             = 'All ' . $new_name; // . 's';
    $labels->archives              = $new_name . ' Archives';
    $labels->attributes            = $new_name . ' Attributes';
    $labels->insert_into_item      = 'Insert into ' . $singular_name;
    $labels->uploaded_to_this_item = 'Uploaded to this ' . $singular_name;
    $labels->featured_image        = 'Featured image for this ' . $singular_name;
    $labels->set_featured_image    = 'Set featured image for this ' . $singular_name;
    $labels->remove_featured_image = 'Remove featured image for this ' . $singular_name;
    $labels->use_featured_image    = 'Use as featured image for this ' . $singular_name;
    $labels->menu_name             = $new_name;
    $labels->name_admin_bar        = $new_name;

    return $labels;
}

// add_filter('post_type_labels_post', 'post_rename_labels');

