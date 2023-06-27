<?php

add_filter('timber/context', 'mytheme_timber_context');

function mytheme_timber_context($context)
{
    $context['options'] = get_fields('option');
    return $context;
}
