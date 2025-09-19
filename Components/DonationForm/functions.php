<?php

namespace Flynt\Components\DonationForm;

use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=DonationForm', function ($data) {
  $locale = get_locale();
  $lang = substr($locale, 0, 2); // z. B. "de"

  $data['langcode'] = $lang;

  return $data;
});

add_action('wp_enqueue_scripts', function () {

  wp_enqueue_script('jquery');

  // jQuery Validate for client-side validation
  wp_enqueue_script(
    'jquery-validate',
    get_template_directory_uri() . '/Components/DonationForm/Assets/scripts/jquery.validate.min.js',
    ['jquery'],
    null,
    true
  );

  wp_enqueue_script(
    'fundraisingbox',
    'https://api.fundraisingbox.com/js/jquery.fundraisingbox.min.js',
    ['jquery'],
    null,
    false
  );
}, 5);

Options::addTranslatable('DonationForm', [
  [
    'label' => __('Allgemeine Einstellungen', 'flynt'),
    'name' => 'labelsTab',
    'type' => 'tab',
    'placement' => 'top',
    'endpoint' => 0,
  ],
  [
    'label' => __('Text für Spendenbutton (Standard)', 'flynt'),
    'name' => 'submitButtonTextDefault',
    'type' => 'text',
    'instructions' => __('Text für den allgemeinen Spendenbutton, z. B. „Jetzt Spenden“.', 'flynt'),
    'default_value' => __('Jetzt Spenden', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Text für Spendenbutton (Einmalige Spende)', 'flynt'),
    'name' => 'submitButtonTextOneTime',
    'type' => 'text',
    'instructions' => __('Text für einmalige Spende, „%amount%“ wird durch den Betrag ersetzt, z. B. „Einmalig 50 Euro spenden“.', 'flynt'),
    'default_value' => __('Einmalig %amount% Euro spenden', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Text für Spendenbutton (Monatliche Spende)', 'flynt'),
    'name' => 'submitButtonTextMonthly',
    'type' => 'text',
    'instructions' => __('Text für monatliche Spende, „%amount%“ wird durch den Betrag ersetzt, z. B. „Monatlich 20 Euro spenden“.', 'flynt'),
    'default_value' => __('Monatlich %amount% Euro spenden', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Formularfelder', 'flynt'),
    'name' => 'formLabelsTab',
    'type' => 'tab',
    'placement' => 'top',
    'endpoint' => 0,
  ],
  [
    'label' => __('Titel für Spendenintervall-Abschnitt', 'flynt'),
    'name' => 'labelPanelInterval',
    'type' => 'text',
    'instructions' => __('Überschrift für den Abschnitt zur Auswahl von Einmalig/Monatlich, z. B. „Meine Spende“.', 'flynt'),
    'default_value' => __('Meine Spende', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Text für Intervall „Einmalig“', 'flynt'),
    'name' => 'oneTimeIntervalText',
    'type' => 'text',
    'instructions' => __('Text für die Option „Einmalige Spende“ im Formular, z. B. „Einmalig“.', 'flynt'),
    'default_value' => __('Einmalig', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Text für Intervall „Monatlich“', 'flynt'),
    'name' => 'monthlyIntervalText',
    'type' => 'text',
    'instructions' => __('Text für die Option „Monatliche Spende“ im Formular, z. B. „Monatlich“.', 'flynt'),
    'default_value' => __('Monatlich', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Platzhalter für eigenes Betragsfeld', 'flynt'),
    'name' => 'customAmountPlaceholder',
    'type' => 'text',
    'instructions' => __('Hinweistext im Feld für einen benutzerdefinierten Spendenbetrag, z. B. „Eigener Betrag“.', 'flynt'),
    'default_value' => __('Eigener Betrag', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Titel für Kontaktdaten-Abschnitt', 'flynt'),
    'name' => 'labelContactPanel',
    'type' => 'text',
    'instructions' => __('Überschrift für den Abschnitt mit den Kontaktdaten, z. B. „Meine Kontaktdaten“.', 'flynt'),
    'default_value' => __('Meine Kontaktdaten', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Select Box mobil', 'flynt'),
    'name' => 'labelSalutationSelectBox',
    'type' => 'text',
    'instructions' => __('Label für das Select Box Drop Down, z. B. „Anrede“.', 'flynt'),
    'default_value' => __('Anrede', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Anrede Select Box leere Option „Anrede wählen“', 'flynt'),
    'name' => 'salutationSelectEmptyOption',
    'type' => 'text',
    'instructions' => __('Text für die Anrede der Select Box leere Option „Anrede wählen“ im Formular. Wird nur mobil angezeigt.', 'flynt'),
    'default_value' => __('Anrede wählen', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Anrede „Frau“', 'flynt'),
    'name' => 'salutationMrs',
    'type' => 'text',
    'instructions' => __('Text für die Anrede „Frau“ im Formular.', 'flynt'),
    'default_value' => __('Frau', 'flynt'),
    'wrapper' => ['width' => 33],
  ],
  [
    'label' => __('Anrede „Herr“', 'flynt'),
    'name' => 'salutationMr',
    'type' => 'text',
    'instructions' => __('Text für die Anrede „Herr“ im Formular.', 'flynt'),
    'default_value' => __('Herr', 'flynt'),
    'wrapper' => ['width' => 33],
  ],
  [
    'label' => __('Anrede „Divers“', 'flynt'),
    'name' => 'salutationDivers',
    'type' => 'text',
    'instructions' => __('Text für die neutrale Anrede im Formular, z. B. „Ohne Angabe“.', 'flynt'),
    'default_value' => __('Ohne Angabe', 'flynt'),
    'wrapper' => ['width' => 33],
  ],
  [
    'label' => __('Feld für Vorname', 'flynt'),
    'name' => 'firstNameLabel',
    'type' => 'text',
    'instructions' => __('Label für das Vornamenfeld, z. B. „Vorname“.', 'flynt'),
    'default_value' => __('Vorname', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für Nachname', 'flynt'),
    'name' => 'lastNameLabel',
    'type' => 'text',
    'instructions' => __('Label für das Nachnamenfeld, z. B. „Nachname“.', 'flynt'),
    'default_value' => __('Nachname', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für E-Mail-Adresse', 'flynt'),
    'name' => 'emailAddressLabel',
    'type' => 'text',
    'instructions' => __('Label für das E-Mail-Feld, z. B. „E-Mail-Adresse“.', 'flynt'),
    'default_value' => __('E-Mail-Adresse', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für Telefonnummer', 'flynt'),
    'name' => 'phoneLabel',
    'type' => 'text',
    'instructions' => __('Label für das Telefonnummernfeld, z. B. „Telefonnummer“.', 'flynt'),
    'default_value' => __('Telefonnummer', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Hinweis für Telefonnummer', 'flynt'),
    'name' => 'phoneNoticeText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'instructions' => __('Hinweistext unter dem Telefonfeld, z. B. Zustimmung für Rückrufe.', 'flynt'),
    'default_value' => __('Gern erlaube ich SOS-Humanity mich ggf. anzurufen und mich über ihre Arbeit sowie Möglichkeiten der Unterstützung zu informieren. Diese Einwilligung kann ich jederzeit widerrufen.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Option für Privatspende', 'flynt'),
    'name' => 'labelPrivateDonation',
    'type' => 'text',
    'instructions' => __('Text für die Option „Privatspende“, z. B. „Ich spende als Privatperson“.', 'flynt'),
    'default_value' => __('Ich spende als Privatperson', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Option für Unternehmensspende', 'flynt'),
    'name' => 'labelCompanyDonation',
    'type' => 'text',
    'instructions' => __('Text für die Option „Unternehmensspende“, z. B. „Ich möchte als Unternehmen spenden“.', 'flynt'),
    'default_value' => __('Ich möchte als Unternehmen spenden', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Option für Spendenbescheinigung', 'flynt'),
    'name' => 'labelWantsReceipt',
    'type' => 'text',
    'instructions' => __('Text für die Option zur Anforderung einer Spendenbescheinigung, z. B. „Ja, ich möchte eine Jahresspendenbescheinigung“.', 'flynt'),
    'default_value' => __('Ja, ich möchte eine Jahresspendenbescheinigung erhalten.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Hinweis Spendenbescheinigung (Unternehmen)', 'flynt'),
    'name' => 'labelWantsAutoReceipt',
    'type' => 'text',
    'instructions' => __('Hinweistext für automatische Spendenbescheinigung bei Unternehmensspenden.', 'flynt'),
    'default_value' => __('Die Spendenbescheinigung wird automatisch zugeschickt.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Leave No One Behind Newsletter Checkbox Text', 'flynt'),
    'name' => 'labelWantsLNOBNewsletter',
    'type' => 'text',
    'instructions' => __('Text für die Option zur LNOB Newsletter, z. B. „Ich möchte auch von LeaveNoOneBehind Informationen über ihre Arbeit erhalten.“.', 'flynt'),
    'default_value' => __('Ich möchte auch von LeaveNoOneBehind Informationen über ihre Arbeit erhalten.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Unternehmensname', 'flynt'),
    'name' => 'labelCompanyName',
    'type' => 'text',
    'instructions' => __('Label für das Feld „Unternehmensname“, z. B. „Name des Unternehmens“.', 'flynt'),
    'default_value' => __('Name des Unternehmens', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Straße', 'flynt'),
    'name' => 'labelStreet',
    'type' => 'text',
    'instructions' => __('Label für das Straßenfeld, z. B. „Straße“.', 'flynt'),
    'default_value' => __('Straße', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Hausnummer', 'flynt'),
    'name' => 'labelHouseNumber',
    'type' => 'text',
    'instructions' => __('Label für das Hausnummernfeld, z. B. „Hausnummer“.', 'flynt'),
    'default_value' => __('Hausnummer', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Postleitzahl', 'flynt'),
    'name' => 'labelPostCode',
    'type' => 'text',
    'instructions' => __('Label für das PLZ-Feld, z. B. „Postleitzahl“.', 'flynt'),
    'default_value' => __('Postleitzahl', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Ort', 'flynt'),
    'name' => 'labelCity',
    'type' => 'text',
    'instructions' => __('Label für das Ortsfeld, z. B. „Ort“.', 'flynt'),
    'default_value' => __('Ort', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für Land', 'flynt'),
    'name' => 'labelCountry',
    'type' => 'text',
    'instructions' => __('Label für das Länderfeld, z. B. „Land“.', 'flynt'),
    'default_value' => __('Land', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Titel für Zahlungsarten-Abschnitt', 'flynt'),
    'name' => 'labelPaymentMethodPanel',
    'type' => 'text',
    'instructions' => __('Überschrift für den Abschnitt zur Auswahl der Zahlungsart, z. B. „Meine Zahlart“.', 'flynt'),
    'default_value' => __('Meine Zahlart', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Feld für SEPA-Kontoinhaber', 'flynt'),
    'name' => 'labelBankAccountOwner',
    'type' => 'text',
    'instructions' => __('Label für das Feld „Kontoinhaber“ bei SEPA-Zahlungen, z. B. „Kontoinhaber/-in“.', 'flynt'),
    'default_value' => __('Kontoinhaber/-in', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für IBAN', 'flynt'),
    'name' => 'labelBankIban',
    'type' => 'text',
    'instructions' => __('Label für das IBAN-Feld, z. B. „IBAN“.', 'flynt'),
    'default_value' => __('IBAN', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für Kreditkarteninhaber', 'flynt'),
    'name' => 'labelCreditCardOwner',
    'type' => 'text',
    'instructions' => __('Label für das Feld „Karteninhaber“ bei Kreditkartenzahlungen, z. B. „Karteninhaber/-in“.', 'flynt'),
    'default_value' => __('Karteninhaber/-in', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für Kreditkartennummer', 'flynt'),
    'name' => 'labelCreditCardNumber',
    'type' => 'text',
    'instructions' => __('Label für das Feld „Kartennummer“, z. B. „Kartennummer“.', 'flynt'),
    'default_value' => __('Kartennummer', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für Kreditkarten-Gültigkeit', 'flynt'),
    'name' => 'labelCreditCardExpiry',
    'type' => 'text',
    'instructions' => __('Label für das Feld „Gültig bis“ bei Kreditkarten, z. B. „Gültig bis“.', 'flynt'),
    'default_value' => __('Gültig bis', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Feld für Kreditkarten-Prüfnummer', 'flynt'),
    'name' => 'labelCreditCardSecureId',
    'type' => 'text',
    'instructions' => __('Label für das Feld „Prüfnummer“ bei Kreditkarten, z. B. „Prüfnummer“.', 'flynt'),
    'default_value' => __('Prüfnummer', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Hinweis für Pflichtfelder', 'flynt'),
    'name' => 'mandatoryFields',
    'type' => 'text',
    'instructions' => __('Text, der Pflichtfelder markiert, z. B. „Pflichtfelder“.', 'flynt'),
    'default_value' => __('Pflichtfelder', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Hinweistext für PayPal', 'flynt'),
    'name' => 'paypalInfoText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'instructions' => __('Hinweistext für PayPal-Zahlungen, z. B. Weiterleitung zu PayPal.', 'flynt'),
    'default_value' => __('Klick auf den „Jetzt spenden“-Button. Du wirst automatisch zu PayPal weitergeleitet, wo du deine Spende abschließen kannst.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Hinweistext für Apple Pay', 'flynt'),
    'name' => 'applePayInfoText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'instructions' => __('Hinweistext für Apple Pay-Zahlungen, z. B. Anweisungen zur Zahlungsfreigabe.', 'flynt'),
    'default_value' => __('Klick auf den „Jetzt spenden“-Button. Die Zahlungsfreigabe erfolgt über Apple Pay. Folge den Anweisungen, um deine Spende abzuschließen.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Hinweistext für Google Pay', 'flynt'),
    'name' => 'googlePayInfoText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'instructions' => __('Hinweistext für Google Pay-Zahlungen, z. B. Anweisungen zur Zahlungsfreigabe.', 'flynt'),
    'default_value' => __('Klick auf den „Jetzt spenden“-Button. Die Zahlungsfreigabe erfolgt über Google Pay. Folge den Anweisungen, um deine Spende abzuschließen.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
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
        'label' => __('Bitte das Formular auswählen (je Sprache)', 'flynt'),
        'name' => 'hash',
        'type' => 'select',
        'choices' => [
          '0rtnskztep1awzb6' => 'Formular Deutsch',
          '4jppw6zr4ckbxmfl' => 'Formular English',
          'b8x47ctvkjzseldm' => 'Formular Italian',
        ],
        'required' => 1,
        'ui' => 1,
        'return_format' => 'value',
      ],
      [
        'label' => __('Intervall', 'flynt'),
        'instructions' => __('Wähle das Intervall aus welches zuerst angezeigt werden soll.', 'flynt'),
        'name' => 'interval',
        'type' => 'radio',
        'choices' => [
          '0' => __('einmalig', 'flynt'),
          '1' => __('monatlich', 'flynt'),
        ],
        'required' => 1,
        'default_value' => '0',
        'layout' => 'horizontal',
      ],
      [
        'label' => __('Mindestspendenbetrag', 'flynt'),
        'name' => 'minAmount',
        'type' => 'number',
        'instructions' => __('Trage hier den Mindestbetrag ein. Bitte mit der Fundraisingbox abgleichen.', 'flynt'),
        'required' => 1,
        'step' => 1,
        'min' => 1,
      ],
      [
        'label' => __('Maximalspendenbetrag', 'flynt'),
        'name' => 'maxAmount',
        'type' => 'number',
        'instructions' => __('Trage hier den Maximalbetrag ein. Bitte mit der Fundraisingbox abgleichen.', 'flynt'),
        'required' => 1,
        'step' => 1,
        'min' => 1,
      ],
      [
        'label' => __('Beitragsvorschläge je Intervall (max 2)', 'flynt'),
        'name' => 'amounts',
        'type' => 'repeater',
        'button_label' => __('Add Interval Amounts', 'flynt'),
        'min' => 2,
        'max' => 2,
        'sub_fields' => [
          [
            'label' => __('Interval Type', 'flynt'),
            'name' => 'interval_type',
            'type' => 'radio',
            'choices' => [
              '0' => __('einmalig', 'flynt'),
              '1' => __('monatlich', 'flynt'),
            ],
            'default_value' => '0',
            'layout' => 'horizontal',
            'required' => 1,
          ],
          [
            'label' => __('Betrags Vorschläge', 'flynt'),
            'name' => 'amount_options',
            'type' => 'repeater',
            'button_label' => __('Add Amount Option', 'flynt'),
            'min' => 4,
            'max' => 4,
            'sub_fields' => [
              [
                'label' => __('Betrag (bitte 4 Beträge auswählen!)', 'flynt'),
                'name' => 'value',
                'type' => 'number',
                'min' => 5,
                'max' => 50000,
              ],
              [
                'label' => __('Ausgewählter Betrag je Intervall (bitte nur einen auswählen)', 'flynt'),
                'name' => 'default',
                'type' => 'true_false',
                'ui' => 1,
              ],
            ],
          ],
        ],
      ],
      [
        'label' => __('Nudge Text', 'flynt'),
        'name' => 'nudge_text',
        'type' => 'wysiwyg',
        'toolbar' => 'basic',
        'tabs' => 'visual',
        'media_upload' => 0,
        'required' => 0,
      ],
      [
        'label' => __('Nudge Text anzeigen', 'flynt'),
        'name' => 'show_nudge_text',
        'type' => 'true_false',
        'ui' => 1,
        'default_value' => 0,
        'required' => 0,
      ],
      [
        'label' => __('Wertversprechen', 'flynt'),
        'name' => 'value_proposition',
        'type' => 'wysiwyg',
        'tabs' => 'visual',
        'media_upload' => 0,
        'required' => 0,
      ],
      [
        'label' => __('Wertversprechen anzeigen', 'flynt'),
        'name' => 'show_value_proposition',
        'type' => 'true_false',
        'ui' => 1,
        'default_value' => 0,
        'required' => 0,
      ],
      [
        'label' => __('Hinweis sicheres Spenden', 'flynt'),
        'name' => 'security_notice',
        'type' => 'wysiwyg',
        'toolbar' => 'basic',
        'tabs' => 'visual',
        'media_upload' => 0,
        'required' => 1,
      ],
      [
        'label' => __('Datenschutz Hinweis', 'flynt'),
        'name' => 'privacy_notice',
        'type' => 'wysiwyg',
        'toolbar' => 'basic',
        'tabs' => 'visual',
        'media_upload' => 0,
        'required' => 1,
      ],
      [
        'label' => __('Danke-Seite Einmalspende', 'flynt'),
        'name' => 'thx_one_time',
        'type' => 'link',
        'required' => 1,
      ],
      [
        'label' => __('Danke-Seite Dauerspende', 'flynt'),
        'name' => 'thx_recurring',
        'type' => 'link',
        'required' => 1,
      ],
    ],
  ];
}

// for debugging
//add_action('wp_footer', function () {
//    echo do_shortcode('[fb_capi_thanks_example]');
//});

