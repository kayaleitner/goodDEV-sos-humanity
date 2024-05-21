<?php

add_filter('rest_authentication_errors', 'disable_rest_api');

function disable_rest_api($access)
{
    // List of allowed endpoints
    $allowed_endpoints = [
        '/wp-json/contact-form-7/v1/',
        '/wp-json/filebird/',
        '/wp-json/redirection/v1/',
        '/wp-json/yoast/v1/',
        '/wp-json/wp/v2/media',
    ];

    // Check if the current request URI matches any allowed endpoints
    foreach ($allowed_endpoints as $endpoint) {
        if (strpos($_SERVER['REQUEST_URI'], $endpoint) !== false) {
            return $access;
        }
    }

    // Deny access to all other REST API routes
    return new WP_Error('rest_disabled', __('The REST API on this site has been disabled.'), ['status' => rest_authorization_required_code()]);
}
