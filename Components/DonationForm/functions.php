<?php

namespace Flynt\Components\DonationForm;

require_once __DIR__ . '/acf-fields.php';
require_once __DIR__ . '/validations.php';
require_once __DIR__ . '/scripts.php';

add_filter('Flynt/addComponentData?name=DonationForm', function ($data) {
  $locale = get_locale();
  $data['langcode'] = substr($locale, 0, 2); // z. B. "de"
  return $data;
});