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
        'label' => __('Barometer Text', 'flynt'),
        'name' => 'barometer_text',
        'type' => 'wysiwyg',
        'toolbar' => 'basic',
        'tabs' => 'visual',
        'media_upload' => 0,
        'instructions' => __('Text, der über dem Barometer angezeigt wird. Mögliche Variablen: {donor_count}, {current_amount}, {goal_amount}', 'flynt'),
        'required' => 0,
      ],
      [
        'label' => __('Text über aktuellem Betrag/Anzahl', 'flynt'),
        'name' => 'barometer_title_current',
        'type' => 'text',
        'instructions' => __('Je nach Einstellung unter Anzeigetyp wird der aktuelle Betrag/Anzahl als Euro oder Anzahl angezeigt.', 'flynt'),
      ],
      [
        'label' => __('Text Zielbetrag oder Anzahl', 'flynt'),
        'name' => 'barometer_text_goal_amount',
        'type' => 'text',
        'instructions' => __('Dieser Text steht vor dem Zielbetrag.', 'flynt'),
        'required' => 1,
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
          'sum' => __('Gesamt Spendensumme', 'flynt'),
          'count' => __('Anzahl Spender', 'flynt'),
        ],
        'required' => 1,
      ],
    ],
  ];
}
