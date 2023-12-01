<?php

// @TODO: needs refactoring

add_filter('rest_authentication_errors', 'disable_rest_api');

function disable_rest_api($access)
{
    // Allow access to specific Contact Form 7 endpoints
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/contact-form-7/v1/') !== false) {
        return $access;
    }

    // Allow access to Redirection plugin endpoints
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/redirection/v1/') !== false) {
        return $access;
    }

    // Allow access to Yoast SEO REST API endpoints (adjust if Yoast uses different endpoints in the future)
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/yoast/v1/') !== false) {
        return $access;
    }
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/media/') !== false) {
        return $access;
    }

    // Allow access to Filebird endpoints
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/filebird/v1/') !== false) {
        return $access;
    }

    // Deny access to all other REST API routes
    return new WP_Error('rest_disabled', __('The REST API on this site has been disabled.'), ['status' => rest_authorization_required_code()]);
}
