<?php

add_filter('get_user_option_admin_color', 'update_user_option_admin_color', 5);

function update_user_option_admin_color($color_scheme)
{
    $color_scheme = 'modern';

    return $color_scheme;
}
