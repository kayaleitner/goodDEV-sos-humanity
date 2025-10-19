<?php

namespace Flynt\Components\DonationBarometer;

/**
 * Provides the ACF (Advanced Custom Fields) layout definition for the
 * DonationBarometer component.
 *
 * This layout is used to configure Donation Barometer via the WordPress backend.
 *
 * @return array
 *   The ACF layout definition array for the DonationBarometer component.
 */
function getACFLayout(): array {
  return [
    'name' => 'DonationBarometer',
    'label' => __('Donation Barometer', 'flynt'),
    'sub_fields' => [
      [
        'label' => __('Title', 'flynt'),
        'name' => 'title',
        'type' => 'text',
      ],
      [
        'label' => __('Zielbetrag oder Anzahl', 'flynt'),
        'name' => 'goal_amount',
        'type' => 'number',
        'instructions' => __('Je nach Einstellung unter Anzeigetyp wird das Ziel als Euro oder Anzahl angezeigt.', 'flynt'),
        'required' => 1,
      ],
      [
        'label' => __('Search ID', 'flynt'),
        'name' => 'search_id',
        'type' => 'text',
        'instructions' => __('Die ID des Smart Filters aus der FundraisingBox. Bitte sicherstellen, dass dieser Filter existiert.', 'flynt'),
        'required' => 1,
      ],
      [
        'label' => __('Anzeigetyp', 'flynt'),
        'name' => 'display_type',
        'type' => 'select',
        'instructions' => __('Auswahl, ob der Spendenbetrag oder die Anzahl der Spender angezeigt werden soll.', 'flynt'),
        'choices' => [
          'sum' => __('Total Sum', 'flynt'),
          'count' => __('Donor Count', 'flynt'),
        ],
        'required' => 1,
      ],
    ],
  ];
}
