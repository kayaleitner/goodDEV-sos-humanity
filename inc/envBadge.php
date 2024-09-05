<?php

// make sure the following code is in your wp-config.php
// set WP env type to local if on local
// if (preg_match("/.*\.local$/", $_SERVER['HTTP_HOST'])){
// 	define( 'WP_ENVIRONMENT_TYPE', 'local' );
// }

// also this feature requires some css as seen in assets/styles/_env-badge.css

// Add a class to the body tag in the admin that reflects the environment type
function add_environment_class_to_admin_body($classes) {
    if (defined('WP_ENVIRONMENT_TYPE')) {
        $classes .= ' env_badge env_' . WP_ENVIRONMENT_TYPE;
    }
    return $classes;
}
add_filter('admin_body_class', 'add_environment_class_to_admin_body');
