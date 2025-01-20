<?php

use ACFComposer\ACFComposer;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'peopleMeta',
        'title' => 'Personal Info',
        'fields' => [
            [
                'label' => __('Function', 'flynt'),
                'name' => 'function',
                'type' => 'text',
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Biography', 'flynt'),
                'name' => 'biography',
                'type' => 'textarea',
                'wrapper' => [
                    'width' => '100',
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'people',
                ],
            ],
        ],
    ]);
});
