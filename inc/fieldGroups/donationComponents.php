<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function() {
  // Flexible Components specifically for Donation post type
  ACFComposer::registerFieldGroup([
    'name' => 'donationComponents',
    'title' => 'Donation Components',
    'style' => 'seamless',
    'fields' => [
      [
        'name' => 'donationComponents',
        'label' => __('Donation Blocks', 'flynt'),
        'type' => 'flexible_content',
        'button_label' => __('Add Block', 'flynt'),
        'layouts' => [
          Components\DonationHero\getACFLayout(),
          Components\DonationForm\getACFLayout(),
          Components\ProgressDonation\getACFLayout(),
          Components\BlockWysiwyg\getACFLayout(),
        ],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'donation',
        ],
      ],
    ],
  ]);
});
