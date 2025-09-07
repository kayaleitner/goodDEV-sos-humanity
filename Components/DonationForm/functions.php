<?php

namespace Flynt\Components\DonationForm;

use Flynt\Utils\Options;

add_action('wp_enqueue_scripts', function () {

  wp_enqueue_script('jquery');
  wp_enqueue_script(
    'fundraisingbox',
    'https://api.fundraisingbox.com/js/jquery.fundraisingbox.min.js',
    ['jquery'],
    null,
    false
  );
}, 5);

// Translatable Options für Labels / Button Text / Hashes
Options::addTranslatable('DonationForm', [
  [
    'label' => __('Labels', 'flynt'),
    'name' => 'labelsTab',
    'type' => 'tab',
    'placement' => 'top',
    'endpoint' => 0
  ],
  [
    'label' => __('Submit Button Text', 'flynt'),
    'name' => 'buttonText',
    'type' => 'text',
    'default_value' => __('Jetzt spenden', 'flynt'),
    'wrapper' => ['width' => 50]
  ],
  [
    'label' => __('Placeholder Amount', 'flynt'),
    'name' => 'amountPlaceholder',
    'type' => 'text',
    'default_value' => __('Betrag eingeben', 'flynt'),
    'wrapper' => ['width' => 50]
  ],
  [
    'label' => __('FundraisingBox Hash per Language', 'flynt'),
    'name' => 'hashes',
    'type' => 'repeater',
    'button_label' => __('Add Language Hash', 'flynt'),
    'sub_fields' => [
      ['label'=>'Language','name'=>'lang','type'=>'select','choices'=>['DE'=>'DE','EN'=>'EN','IT'=>'IT']],
      ['label'=>'Hash','name'=>'hash','type'=>'text']
    ]
  ]
]);


/**
 * Provides the ACF (Advanced Custom Fields) layout definition for the DonationForm component.
 *
 * This layout is used to configure donation forms via the WordPress backend.
 * It defines fields for the FundraisingBox form hash, donation intervals,
 * and specific donation amount options per interval.
 *
 * @return array
 *   The ACF layout definition array for the DonationForm component.
 */
function getACFLayout(): array {
  return [
    'name' => 'DonationForm',
    'label' => 'Donation Form',
    'sub_fields' => [
      [
        'label' => __('Select the FundraisingBox form', 'flynt'),
        'instructions' => __('Choose the correct form for each language from the dropdown.', 'flynt'),
        'name' => 'hash',
        'type' => 'select',
        'choices' => [
          '0rtnskztep1awzb6' => 'Form Deutsch',
          '4jppw6zr4ckbxmfl' => 'Form English',
          'b8x47ctvkjzseldm' => 'Form Italian',
        ],
        'required' => 1,
        'ui' => 1,
        'return_format' => 'value',
      ],
      [
        'label' => __('Interval', 'flynt'),
        'name' => 'interval',
        'type' => 'radio',
        'choices' => [
          '0' => __('One-Time', 'flynt'),
          '1' => __('Monthly', 'flynt'),
        ],
        'default_value' => '0',
        'layout' => 'horizontal',
      ],
      [
        'label' => __('Amounts per Interval', 'flynt'),
        'name' => 'amounts',
        'type' => 'repeater',
        'button_label' => __('Add Interval Amounts', 'flynt'),
        'min' => 1,
        'max' => 2,
        'sub_fields' => [
          [
            'label' => __('Interval Type', 'flynt'),
            'name' => 'interval_type',
            'type' => 'radio',
            'choices' => [
              '0' => __('One-Time', 'flynt'),
              '1' => __('Monthly', 'flynt'),
            ],
            'default_value' => '0',
          ],
          [
            'label' => __('Amount Options', 'flynt'),
            'name' => 'amount_options',
            'type' => 'repeater',
            'button_label' => __('Add Amount Option', 'flynt'),
            'min' => 3,
            'max' => 4,
            'sub_fields' => [
              [
                'label' => __('Amount', 'flynt'),
                'name' => 'value',
                'type' => 'number',
              ],
              [
                'label' => __('Default', 'flynt'),
                'name' => 'default',
                'type' => 'true_false',
                'ui' => 1,
              ],
            ],
          ],
        ],
      ],
    ],
  ];
}

//add_action('wp_footer', function () {
//    echo do_shortcode('[fb_capi_thanks_example]');
//});

