<?php

namespace Flynt\Components\DonationForm;

/**
 * Validates that exactly one default amount is selected per interval repeater.
 */
add_filter('acf/validate_value/type=repeater', function ($valid, $value, $field, $input) {
  $intervalRepeaters = [
    'amounts_one_time',
    'amounts_monthly',
    'amounts_quarterly',
    'amounts_half_yearly',
    'amounts_yearly',
  ];

  if (!in_array($field['name'], $intervalRepeaters)) {
    return $valid;
  }

  if (empty($value) || !is_array($value)) {
    return $valid;
  }

  $defaultCount = 0;
  foreach ($value as $row) {
    foreach ($row as $key => $val) {
      if (str_ends_with($key, '_default') && $val === '1') {
        $defaultCount++;
      }
    }
  }

  if ($defaultCount !== 1) {
    return __('Bitte wähle genau einen Standard-Betrag für dieses Intervall aus.', 'flynt');
  }

  return $valid;
}, 10, 4);

/**
 * Validate the "active_intervals" ACF field.
 */
add_filter('acf/validate_value/name=active_intervals', function ($valid, $value, $field, $input) {
  if (!$valid) {
    return $valid;
  }

  $count = is_array($value) ? count($value) : 0;

  if ($count < 1) {
    return __('Bitte wähle mindestens ein Intervall aus.', 'flynt');
  }

  if ($count > 3) {
    return __('Du kannst maximal drei Intervalle auswählen.', 'flynt');
  }

  return $valid;
}, 10, 4);

/**
 * Validate "interval" field → must be one of the selected "active_intervals".
 */
add_filter('acf/validate_value/name=interval', function ($valid, $value, $field, $input_name) {
  if (!$valid) {
    return $valid;
  }

  $active_intervals_key = 'field_pageComponents_pageComponents_DonationForm_active_intervals';
  $acf_data = $_POST['acf'] ?? [];

  $active_intervals = [];
  if (!empty($acf_data['field_pageComponents_pageComponents'])) {
    foreach ($acf_data['field_pageComponents_pageComponents'] as $row) {
      if (($row['acf_fc_layout'] ?? '') === 'DonationForm' && isset($row[$active_intervals_key])) {
        $active_intervals = $row[$active_intervals_key];
        break;
      }
    }
  }

  if (empty($active_intervals) && !empty($_POST['post_id'])) {
    $active_intervals = get_field('active_intervals', $_POST['post_id']) ?: [];
  }

  $active_intervals = array_map('strval', (array) $active_intervals);

  if (!empty($active_intervals) && !in_array(strval($value), $active_intervals, true)) {
    return __('Das ausgewählte Intervall muss in den ausgewählten aktiven Intervallen enthalten sein.', 'flynt');
  }

  return $valid;
}, 10, 4);