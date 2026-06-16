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
        '/wp-json/flynt/v1/submit_form',
        '/wp-json/fb-capi/v1/',
        // Allow Borlabs Cookie REST endpoints (needed for consent saving/statistics)
        '/wp-json/borlabs-cookie/v1/',
        '/wp-json/sos/v1/barometer/embed',
        '/wp-json/sos/v1/donorlist/embed',
        // TEMPORARY: debug endpoint for the per-action recurring data layer (remove with inc/frboxBarometerDebug.php)
        '/wp-json/sos/v1/barometer/debug',
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
