<?php

add_action('Flynt/afterRegisterComponents', function () {
    \ACFComposer\ACFComposer::registerFieldGroup([
        'name' => 'actionMeta',
        'title' => 'Action Meta',
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
        'fields' => [
            [
                'label' => __('Icon', 'flynt'),
                'instructions' => __('Image-Format: PNG, SVG.', 'flynt'),
                'name' => 'icon',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'png,svg'
            ],
            [
                'label' => 'Page Link',
                'name' => 'page_link',
                'type' => 'link',
            ]
        ],
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'action',
                ),
            ),
        ),
    ]);
});