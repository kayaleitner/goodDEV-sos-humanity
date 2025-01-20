<?php

use ACFComposer\ACFComposer;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'jobsMeta',
        'title' => 'Job Info',
        'fields' => [
            [
                'label' => __('Job Category', 'flynt'),
                'name' => 'jobCategory',
                'type' => 'text',
                'instructions' => __('Enter the job category.', 'flynt'),
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Location', 'flynt'),
                'name' => 'location',
                'type' => 'text',
                'instructions' => __('Enter the location of the job.', 'flynt'),
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'instructions' => __('Enter the job description.', 'flynt'),
                'wrapper' => [
                    'width' => '100',
                ],
            ],
            [
                'label' => __('URL', 'flynt'),
                'name' => 'url',
                'type' => 'url',
                'instructions' => __('Enter the application URL.', 'flynt'),
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
                    'value' => 'job',
                ],
            ],
        ],
    ]);
});