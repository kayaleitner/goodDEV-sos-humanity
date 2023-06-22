<?php

add_filter('rest_authentication_errors', 'disable_rest_api');
function disable_rest_api($access)
{
    return new WP_Error('rest_disabled', __('The REST API on this site has been disabled.'), ['status' => rest_authorization_required_code()]);
}
