<?php

namespace Flynt\Components\HeroLottieAnimation;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'HeroLottieAnimation',
        'label' => __('Hero: Lottie Animation', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                // 'instructions' => __('Upload the lottie animation file here.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text'
            ],
            // [
            //     'label' => __('Animation', 'flynt'),
            //     'instructions' => __('Upload the lottie animation file here.', 'flynt'),
            //     'name' => 'animation',
            //     'type' => 'image',
            //     'preview_size' => 'medium',
            //     'required' => 0,
            //     'mime_types' => 'jpg,jpeg,png,svg'
            // ],
            // [
            //     'label' => __('Animation', 'flynt'),
            //     'instructions' => __('Upload the lottie animation file here.', 'flynt'),
            //     'name' => 'animationFile',
            //     'type' => 'file',
            //     'return_format' => 'url',
            //     'mime_types' => 'json'
            // ],
            [
                'label' => __('Lottie Animation Link', 'flynt'),
                'instructions' => __('Upload the lottie animation file here.', 'flynt'),
                'name' => 'lottieAnimationLink',
                'type' => 'url',
            ],
        ]
    ];
}
