<?php

function theme_add_color_palette()
{

    add_theme_support(
        'editor-color-palette',
        '#000',
        '#fff',
        '#169b83',
        '#272a5f',
        '#52b756',
        '#6ff69d',
    );
}
add_action('init', 'theme_add_color_palette');
