<?php

namespace Flynt\Components\DonationBarometer;

/**
 * Provides the ACF (Advanced Custom Fields) layout definition for the
 * DonationBarometer component.
 *
 * This layout is used to configure the Donation Barometer via the WordPress backend.
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
        'label' => __('Barometer Text', 'flynt'),
        'name' => 'barometer_text',
        'type' => 'wysiwyg',
        'tabs' => 'visual',
        'media_upload' => 0,
        'instructions' => __(
          '<strong>Beschreibung:</strong><br>
          Dieser Text wird <strong>oberhalb</strong> des Barometers angezeigt.<br>
          Verwende folgende Variablen, um dynamische Werte einzufügen:<br>
          <code>{donor_count}</code> – Anzahl der Spender:innen<br>
          <code>{current_amount}</code> – aktueller Spendenbetrag<br>
          <code>{goal_amount}</code> – Zielwert (abhängig vom Anzeigetyp)',
          'flynt'
        ),
        'required' => 0,
      ],
      [
        'label' => __('Text aktueller Betrag/Anzahl', 'flynt'),
        'name' => 'barometer_text_current',
        'type' => 'wysiwyg',
        'tabs' => 'visual',
        'media_upload' => 0,
        'instructions' => __(
          '<strong>Beschreibung:</strong><br>
          Dieser Text beschreibt den <strong>aktuellen Fortschritt</strong> des Barometers.<br>
          Verwende folgende Variablen, um dynamische Werte einzufügen:<br>
          <code>{current_amount}</code> – aktueller Spendenbetrag<br>
          <code>{donor_count}</code> – aktuelle Anzahl der Spender:innen<br><br>
          Beispiel:<br>
          <em>„Wir haben bereits {current_amount} € von {donor_count} Menschen gesammelt!“</em>',
          'flynt'
        ),
        'required' => 1,
      ],
      [
        'label' => __('Anzeigetyp', 'flynt'),
        'name' => 'display_type',
        'type' => 'select',
        'instructions' => __(
          '<strong>Beschreibung:</strong><br>
          Legt fest, ob das Barometer auf der <strong>Gesamtsumme</strong> der Spenden oder der <strong>Anzahl der Spender:innen</strong> basiert.<br>
          Diese Einstellung beeinflusst die Berechnung und Darstellung im Frontend – insbesondere im Zusammenspiel mit dem Feld <code>Zielbetrag oder Anzahl</code>.',
          'flynt'
        ),
        'choices' => [
          'sum' => __('Gesamt Spendensumme', 'flynt'),
          'count' => __('Anzahl Spender:innen', 'flynt'),
        ],
        'required' => 1,
      ],
      [
        'label' => __('Zielbetrag oder Anzahl', 'flynt'),
        'name' => 'goal_amount',
        'type' => 'number',
        'instructions' => __(
          '<strong>Wichtiger Hinweis:</strong><br>
          Dieses Feld ist eng mit der Einstellung <code>Anzeigetyp</code> verknüpft.<br>
          - Wenn <strong>Gesamt Spendensumme</strong> gewählt wurde, wird der Wert als <strong>Eurobetrag {current_amount}</strong> interpretiert.<br>
          – Wenn <strong>Anzahl Spender:innen</strong> gewählt wurde, wird der Wert als <strong>Anzahl {donor_count}</strong> interpretiert.<br>
          Das Barometer berechnet und visualisiert den Fortschritt automatisch auf Basis dieser Auswahl.',
          'flynt'
        ),
        'required' => 1,
      ],
      [
        'label' => __('Search ID', 'flynt'),
        'name' => 'search_id',
        'type' => 'text',
        'instructions' => __(
          '<strong>Beschreibung:</strong><br>
          Die <code>Search ID</code> ist die Kennung des Smart Filters aus der FundraisingBox.<br>
          Stelle sicher, dass dieser Filter dort existiert und die passenden Spenden oder Kontakte liefert.',
          'flynt'
        ),
        'required' => 1,
      ],
      [
        'label' => __('Text Zielbetrag oder Anzahl', 'flynt'),
        'name' => 'barometer_text_goal_amount',
        'type' => 'wysiwyg',
        'tabs' => 'visual',
        'media_upload' => 0,
        'instructions' => __(
          '<strong>Beschreibung:</strong><br>
          Text, der den <strong>Zielwert</strong> beschreibt (abhängig vom gewählten Anzeigetyp).<br>
          Verwende die Variable:<br>
          <code>{goal_amount}</code> – Zielbetrag (Euro) oder Zielanzahl (Spender:innen)',
          'flynt'
        ),
        'required' => 0,
      ],
    ],
  ];
}
