<?php

namespace Flynt\Components\DonationForm;

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script('jquery');

  wp_enqueue_script(
    'jquery-validate',
    get_template_directory_uri() . '/Components/DonationForm/Assets/scripts/jquery.validate.min.js',
    ['jquery'],
    null,
    true
  );

  wp_enqueue_script(
    'fundraisingbox',
    'https://secure.fundraisingbox.com/js/jquery.fundraisingbox.min.js',
    ['jquery'],
    null,
    false
  );
}, 5);