/* eslint camelcase: 0 */


import { transformSelectToRadios, transformSelectToCheckbox } from './utils/utils'

export default function (component) {
  const $ = window.jQuery;
  const $form = $(component).find('#fbx-donation-form');
  const hash = $form.data('fbxFormHash');
  if (!hash || typeof $form.fundraisingBoxForm !== 'function') return;

  const classes = {
    row: 'fbx-field space-y-1',
    label: 'block text-sm font-medium text-gray-700 mb-1',
    text: 'w-full border rounded px-3 py-2',
    select: 'w-full border rounded px-3 py-2',
    checkbox: 'mr-2',
    radio: 'mr-2',
    error: 'text-sm text-red-600 mt-1'
  };

  const myForm = $form.fundraisingBoxForm({ hash, classes });

  const have = (key, available) => available.includes(key);

  function toggleAddressSection(available) {
    const hasCompany = have('company_name', available) && $(component).find('[name="payment[company_name]"]').val()?.trim()?.length > 0;
    const wantsReceipt = have('wants_receipt', available) && $(component).find('[name="payment[wants_receipt]"]').is(':checked');
    $(component).find('#address-section').toggleClass('hidden', !(hasCompany || wantsReceipt));
  }

  myForm.on('fundraisingBox:init', () => {
    const fields = myForm.getFormFields?.() || {};
    const available = Object.keys(fields);
    console.log('fields', fields);

    // Intervall
    if (have('interval', available)) {
      myForm.appendFieldRowsTo('#interval-section', ['interval'], { row: 'text-center' });

      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios(
          { id: 'payment_interval' },
          $(component),
          { classes: { wrapper: 'flex flex-wrap gap-3', radio: 'mr-2', label: 'ml-2' } }
        )
      }
    }

    // Betrag
    if (have('amount', available)) {
      myForm.appendFieldRowsTo('#amount-section > div:first', ['amount'], { row: 'fbx-field space-y-1' });
    }

    // Persönliche Daten
    if (have('salutation', available)) {
      myForm.appendFieldRowsTo('#row-salutation', ['salutation'], { row: 'fbx-field space-y-1', select: 'w-full border rounded px-3 py-2' });
    }
    if (have('first_name', available)) {
      myForm.appendFieldRowsTo('#row-name', ['first_name'], { row: 'fbx-field space-y-1' });
    }
    if (have('last_name', available)) {
      myForm.appendFieldRowsTo('#row-name', ['last_name'], { row: 'fbx-field space-y-1' });
    }
    if (have('email', available)) {
      myForm.appendFieldRowsTo('#row-contact', ['email'], { row: 'fbx-field space-y-1' });
    }
    if (have('phone', available)) {
      myForm.appendFieldRowsTo('#row-contact', ['phone'], { row: 'fbx-field space-y-1' });
    }
    // if (have('wants_receipt', available)) {
    //   myForm.appendFieldRowsTo('#row-preferences', ['wants_receipt'], { row: 'fbx-field space-y-1', checkbox: 'mr-2' });
    // }
    if (have('wants_newsletter', available)) {
      myForm.appendFieldRowsTo('#row-wants-newsletter', ['wants_newsletter'], { row: 'fbx-field space-y-1', checkbox: 'mr-2' });
    }

    if (have('wants_receipt', available)) {
      myForm.appendFieldRowsTo('#row-wants-receipt', ['wants_receipt'], { row: 'fbx-field space-y-1', checkbox: 'mr-2' });

      // Example 1: turn the select into radios with all available options
      if (typeof transformSelectToCheckbox === 'function') {
        // transformSelectToRadios(
        //   { id: 'payment_wants_receipt' },
        //   $(component),
        //   { classes: { wrapper: 'flex flex-wrap gap-3', radio: 'mr-2', label: 'ml-2' } }
        // )
        transformSelectToCheckbox(
          { id: 'payment_wants_receipt' },
          $(component),
          { checkboxLabel: 'Spendenquittung jährlich', checkedValue: 'receipt_end_of_year' }
        )
      }

      // Example 2: if you prefer a single checkbox for a specific value, use transformSelectToCheckbox instead

    }

    // Adresse
    if (have('address', available)) {
      myForm.appendFieldRowsTo('#row-address-1', ['address'], { row: 'fbx-field space-y-1' });
    }
    if (have('post_code', available)) {
      myForm.appendFieldRowsTo('#row-address-1', ['post_code'], { row: 'fbx-field space-y-1' });
    }
    if (have('city', available)) {
      myForm.appendFieldRowsTo('#row-address-2', ['city'], { row: 'fbx-field space-y-1' });
    }
    if (have('country', available)) {
      myForm.appendFieldRowsTo('#row-address-2', ['country'], { row: 'fbx-field space-y-1' });
    }

    toggleAddressSection(available);

    // payment methods
    if (have('payment_method', available)) {
      myForm.appendFieldRowsTo('#payment-methods', ['payment_method'], { row: 'fbx-field space-y-1', radio: 'mr-2' });
      // Force radios for payment_method if SDK rendered a select
      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios(
          { id: 'payment_payment_method' },
          $(component),
          { classes: { wrapper: 'flex flex-col gap-2', radio: 'mr-2', label: 'ml-2' } }
        )
      }
      const $paymentFields = $(component).find('#payment-fields');
      const renderPaymentSpecific = () => {
        $paymentFields.empty();
        const current = $(component).find('[name="payment[payment_method]"]:checked').val();
        if (!current) return;
        if (current === 'sepa' || current === 'debit' || current === 'bank') {
          if (have('bank_account_owner', available)) {
            myForm.appendFieldRowsTo('#payment-fields', ['bank_account_owner'], { row: 'fbx-field space-y-1' });
          }
          if (have('bank_iban', available)) {
            myForm.appendFieldRowsTo('#payment-fields', ['bank_iban'], { row: 'fbx-field space-y-1' });
          }
        } else if (current === 'creditcard' || current === 'credit_card') {
          if (have('credit_card_owner', available)) {
            myForm.appendFieldRowsTo('#payment-fields', ['credit_card_owner'], { row: 'fbx-field space-y-1' });
          }
          if (have('credit_card_number', available)) {
            myForm.appendFieldRowsTo('#payment-fields', ['credit_card_number'], { row: 'fbx-field space-y-1' });
          }
          if (have('credit_card_expiry', available)) {
            myForm.appendFieldRowsTo('#payment-fields', ['credit_card_expiry'], { row: 'fbx-field space-y-1' });
          }
          if (have('credit_card_secure_id', available)) {
            myForm.appendFieldRowsTo('#payment-fields', ['credit_card_secure_id'], { row: 'fbx-field space-y-1' });
          }
        }
        // Weitere Zahlmethoden hier ergänzen, falls nötig
        console.log('hshhshsh')
      };
      renderPaymentSpecific();
      $(component).on('change', '[name="payment[payment_method]"]', renderPaymentSpecific);
    }

    // Versteckte Felder
    [
      'parent_url', 'success_redirect_url', 'failure_redirect_url', 'ip', 'user_agent',
      'element_hash', 'donor_covers_the_fee', 'covered_fee_checksum_amount',
      'donation_custom_field_13071', 'message', 'birthday'
    ].forEach((key) => {
      if (have(key, available)) {
        myForm.appendFieldRowsTo('#hidden-fields', [key], { row: 'hidden' });
      }
    });

    toggleAddressSection(available);
  });

  // Adresssichtbarkeit bei Änderung
  $(component).on('input change', '[name="payment[company_name]"], [name="payment[wants_receipt]"]', () => {
    const available = Object.keys(myForm.getFormFields?.() || {});
    toggleAddressSection(available);
  });

  // Session-Sync für Intervall und Betrag
  // function syncSessionFromUI() {
  //   const interval = $('[name="payment[interval]"]:checked', component).val();
  //   const amountRadio = $('[name="payment[amount]"]:checked', component).val();
  //   const amountCustom = $('input[name="amount_custom"]', component).val();
  //   const amount = amountCustom && Number(amountCustom) > 0 ? amountCustom : amountRadio;
  //   const payload = {};
  //   if (interval != null) payload.interval = interval;
  //   if (amount != null) payload.amount = amount;
  //   if (Object.keys(payload).length) myForm.updateSession(payload).then(() => myForm.fillValues());
  // }
  // $(component).on('change input', '[name="payment[interval]"], [name="payment[amount]"], [name="amount_custom"]', syncSessionFromUI);

  myForm.on('fundraisingBox:error', () => myForm.renderErrors());

}