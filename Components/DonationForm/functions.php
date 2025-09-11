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
    'label' => __('General', 'flynt'),
    'name' => 'labelsTab',
    'type' => 'tab',
    'placement' => 'top',
    'endpoint' => 0,
  ],
  [
    'label' => __('Submit Button Text Standard', 'flynt'),
    'name' => 'submitButtonTextDefault',
    'type' => 'text',
    'default_value' => __('Jetzt Spenden', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Submit Button Text Vorlage (einmalig)', 'flynt'),
    'name' => 'submitButtonTextOneTime',
    'type' => 'text',
    'instructions' => __('%amount% ist der Platzhalter für den Spendenbetrag', 'flynt'),
    'default_value' => __('Einmalig %amount% Euro spenden', 'flynt'), // Italienisch
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Submit Button Text Vorlage (monatlich)', 'flynt'),
    'name' => 'submitButtonTextMonthly',
    'type' => 'text',
    'instructions' => __('%amount% ist der Platzhalter für den Spendenbetrag', 'flynt'),
    'default_value' => __('Monatlich %amount% Euro spenden', 'flynt'), // Italienisch
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Formular', 'flynt'),
    'name' => 'formLabelsTab',
    'type' => 'tab',
    'placement' => 'top',
    'endpoint' => 0,
  ],
  [
    'label' => __('Meine Spende', 'flynt'),
    'name' => 'labelPanelInterval',
    'type' => 'text',
    'default_value' => __('Meine Spende', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Interval option one-time', 'flynt'),
    'name' => 'oneTimeIntervalText',
    'type' => 'text',
    'default_value' => __('Einmalig', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Interval option monthly', 'flynt'),
    'name' => 'monthlyIntervalText',
    'type' => 'text',
    'default_value' => __('Monatlich', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Placeholder Eigener Betrag', 'flynt'),
    'name' => 'customAmountPlaceholder',
    'type' => 'text',
    'default_value' => __('Eigener Betrag', 'flynt'),
    'wrapper' => ['width' => 50],
  ],

  [
    'label' => __('Meine Kontaktdaten', 'flynt'),
    'name' => 'labelContactPanel',
    'type' => 'text',
    'default_value' => __('Meine Kontaktdaten', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Salutation Mrs.', 'flynt'),
    'name' => 'salutationMrs',
    'type' => 'text',
    'default_value' => __('Frau', 'flynt'),
    'wrapper' => ['width' => 33],
  ],
  [
    'label' => __('Salutation Mr.', 'flynt'),
    'name' => 'salutationMr',
    'type' => 'text',
    'default_value' => __('Herr', 'flynt'),
    'wrapper' => ['width' => 33],
  ],
  [
    'label' => __('Salutation Divers', 'flynt'),
    'name' => 'salutationDivers',
    'type' => 'text',
    'default_value' => __('Ohne Angabe', 'flynt'),
    'wrapper' => ['width' => 33],
  ],
  [
    'label' => __('Vorname', 'flynt'),
    'name' => 'firstNameLabel',
    'type' => 'text',
    'default_value' => __('Vorname', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Nachname', 'flynt'),
    'name' => 'lastNameLabel',
    'type' => 'text',
    'default_value' => __('Nachname', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('E-Mail-Adresse', 'flynt'),
    'name' => 'emailAddressLabel',
    'type' => 'text',
    'default_value' => __('E-Mail-Adresse', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Telefonnummer', 'flynt'),
    'name' => 'phoneLabel',
    'type' => 'text',
    'default_value' => __('Telefonnummer', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Telefonnummer Hinweis Text', 'flynt'),
    'name' => 'phoneNoticeText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'default_value' => __('Gern erlaube ich SOS-Humanity mich ggf. anzurufen und mich über Ihre Arbeit sowie Möglichkeiten der Unterstützung zu informieren. Diese Einwilligung kann ich jederzeit widerrufen.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Privat spende', 'flynt'),
    'name' => 'labelPrivateDonation',
    'type' => 'text',
    'default_value' => __('Ich spende als Privatperson', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label Unternehmen spende', 'flynt'),
    'name' => 'labelCompanyDonation',
    'type' => 'text',
    'default_value' => __('Ich möchte als Unternehmen spenden', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label Spendenbescheinigung', 'flynt'),
    'name' => 'labelWantsReceipt',
    'type' => 'text',
    'default_value' => __('Ja, ich möchte eine Jahresspendenbescheinigung erhalten.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Spendenbescheinigung wenn Unternehmensspende', 'flynt'),
    'name' => 'labelWantsAutoReceipt',
    'type' => 'text',
    'default_value' => __('Ihre Spendenbescheinigung wird Ihnen automatisch zugeschickt.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Name des Unternehmens', 'flynt'),
    'name' => 'labelCompanyName',
    'type' => 'text',
    'default_value' => __('Name des Unternehmens', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Straße', 'flynt'),
    'name' => 'labelStreet',
    'type' => 'text',
    'default_value' => __('Straße', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Hausnummer', 'flynt'),
    'name' => 'labelHomeNumber',
    'type' => 'text',
    'default_value' => __('Hausnummer', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Postleitzahl', 'flynt'),
    'name' => 'labelPostCode',
    'type' => 'text',
    'default_value' => __('Postleitzahl', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Ort', 'flynt'),
    'name' => 'labelCity',
    'type' => 'text',
    'default_value' => __('Ort', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Land', 'flynt'),
    'name' => 'labelCountry',
    'type' => 'text',
    'default_value' => __('Land', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Meine Zahlart', 'flynt'),
    'name' => 'labelPaymentMethodPanel',
    'type' => 'text',
    'default_value' => __('Meine Zahlart', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Label Sepa Kontoinhaber', 'flynt'),
    'name' => 'labelBankAccountOwner',
    'type' => 'text',
    'default_value' => __('Kontoinhaber/-in', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label IBAN', 'flynt'),
    'name' => 'labelBankIban',
    'type' => 'text',
    'default_value' => __('IBAN', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label Kreditkarten Inhaber', 'flynt'),
    'name' => 'labelCreditCardOwner',
    'type' => 'text',
    'default_value' => __('Karteninhaber/-in', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label Kartennummer', 'flynt'),
    'name' => 'labelCreditCardNumber',
    'type' => 'text',
    'default_value' => __('Kartennummer', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label Kreditkarte Gültig bis', 'flynt'),
    'name' => 'labelCreditCardExpiry',
    'type' => 'text',
    'default_value' => __('Gültig bis', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Label Kreditkarte Prüfnummer', 'flynt'),
    'name' => 'labelCreditCardSecureId',
    'type' => 'text',
    'default_value' => __('Prüfnummer', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('Pflichtfelder', 'flynt'),
    'name' => 'mandatoryFields',
    'type' => 'text',
    'default_value' => __('Pflichtfelder', 'flynt'),
    'wrapper' => ['width' => 50],
  ],
  [
    'label' => __('PayPal Hinweistext', 'flynt'),
    'name' => 'paypalInfoText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'default_value' => __('Bitte klicken Sie den „Jetzt spenden“-Button. Sie werden automatisch zu PayPal weitergeleitet, wo Sie Ihre Spende abschließen können.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Apple Pay Hinweistext', 'flynt'),
    'name' => 'applePayInfoText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'default_value' => __('Bitte klicken Sie den „Jetzt spenden“-Button. Die Zahlungsfreigabe erfolgt über Appl Pay. Folgen Sie den Anweisungen, um die Spende abzuschließen.', 'flynt'),
    'wrapper' => ['width' => 100],
  ],
  [
    'label' => __('Google Pay Hinweistext', 'flynt'),
    'name' => 'googlePayInfoText',
    'type' => 'wysiwyg',
    'toolbar' => 'basic',
    'media_upload' => 0,
    'default_value' => __('Bitte klicken Sie den „Jetzt spenden“-Button. Die Zahlungsfreigabe erfolgt über Google Pay. Folgen Sie den Anweisungen, um die Spende abzuschließen.', 'flynt'),
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
          '0rtnskztep1awzb6' => 'Form Deutsch',
          '4jppw6zr4ckbxmfl' => 'Form English',
          'b8x47ctvkjzseldm' => 'Form Italian',
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

