import {
  transformSelectToRadios,
  transformSelectToCheckbox,
} from './utils/utils'

export default function (component) {
  const $ = window.jQuery;
  const $form = $(component).find('#fbx-donation-form');
  const hash = $form.data('fbxFormHash');
  let availableFields;
  if (!hash || typeof $form.fundraisingBoxForm !== 'function') return;

  const classes = {
    row: 'fbx-field space-y-1',
    label: 'block text-sm font-medium text-gray-700 mb-1',
    text: 'w-full px-3 py-2',
    select: 'w-full px-3 py-2',
    checkbox: 'mr-2',
    radio: 'mr-2',
    error: 'text-sm red mt-1',
  };

  const myForm = $form.fundraisingBoxForm({
    hash,
    classes,
  });

  const have = (key, available) => available.includes(key);

  function toggleAddressSection(available) {
    const $companyInput = $(component).find('[name="payment[company_name]"]');
    const hasCompany = have('company_name', available) && $companyInput.val()?.trim().length > 0;
    const $receiptCheckbox = $(component).find('[name="payment[wants_receipt]"]');
    const wantsReceipt = have('wants_receipt', available) && $receiptCheckbox.is(':checked');

    const $addressWrapper = $(component).find('.row-address-data-wrapper');
    if (hasCompany || wantsReceipt) {
      $addressWrapper.stop(true, true).removeClass('hidden').slideDown(400);
    } else {
      $addressWrapper.stop(true, true).slideUp(400, () => $addressWrapper.addClass('hidden'));
    }
  }

  function togglePaymentFields() {
    const val = $(component).find('[name="payment[payment_method]"]:checked').val();
    const sepaValues = ['sepa_direct_debit', 'wikando_direct_debit', 'bank'];
    const ccValues = ['creditcard', 'credit_card', 'stripe_credit_card'];

    const $sepa = $(component).find('.donation-sepa-fields');
    const $cc = $(component).find('.donation-creditcard-fields');

    if (sepaValues.includes(val)) {
      $sepa.stop(true, true).removeClass('hidden').slideDown(300);
      $cc.stop(true, true).slideUp(300, () => $cc.addClass('hidden'));
    } else if (ccValues.includes(val)) {
      $cc.stop(true, true).removeClass('hidden').slideDown(300);
      $sepa.stop(true, true).slideUp(300, () => $sepa.addClass('hidden'));
    } else {
      $sepa.stop(true, true).slideUp(300, () => $sepa.addClass('hidden'));
      $cc.stop(true, true).slideUp(300, () => $cc.addClass('hidden'));
    }
  }

  function setAmount(value) {
    const $hiddenAmount = $(component).find('[name="payment[amount]"]');
    if ($hiddenAmount.length) $hiddenAmount.val(value);
  }

  myForm.on('fundraisingBox:init', () => {
    const fields = myForm.getFormFields?.() || {};
    availableFields = Object.keys(fields);
    console.log(fields)

    if (have('interval', availableFields)) {
      myForm.appendFieldRowsTo('.donationForm__interval', ['interval'], { row: 'text-center' });

      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios({ id: 'payment_interval' }, $(component), {
          classes: { wrapper: 'donationForm__interval_wrapper' },
        });
      }
    }

    const $defaultIntervalWrapper = $('#payment_interval_wrapper');
    const defaultInterval = $defaultIntervalWrapper.closest('.donationForm__interval').data('default-interval');
    $(`input[name="payment[interval]"][value="${defaultInterval}"]`).prop('checked', true).trigger('change');

    $(component).on('change', 'input[name="payment[interval]"]', function () {
      const interval = $(this).val();
      $('.amount-group-wrapper').removeClass('active').hide();
      const $activeGroup = $(`.amount-group-wrapper[data-interval="${interval}"]`).addClass('active').show();
      const $custom = $(component).find('#custom_amount');
      const customVal = $custom.val()?.trim();
      if (!customVal) {
        const $defaultRadio = $activeGroup.find('input.amount-radio:checked').first();
        if ($defaultRadio.length) setAmount($defaultRadio.val());
      } else {
        setAmount(customVal);
        $activeGroup.find('input.amount-radio').prop('checked', false);
      }
    });

    const personFields = ['first_name', 'last_name', 'company_name', 'email', 'phone'];
    personFields.forEach((field, index) => {
      if (!have(field, availableFields)) return;
      myForm.appendFieldRowsTo('.row-person-data', [field]);
      const $lastField = $('.row-person-data > div:last-child');
      if (index === 2) {
        $lastField.addClass('col-span-1 sm:col-span-2');
      } else {
        $lastField.addClass('col-span-1');
      }
    });

    if (have('salutation', availableFields)) {
      myForm.appendFieldRowsTo('#row-salutation', ['salutation'], {
        row: 'fbx-field space-y-1',
        select: 'w-full px-3 py-2',
      });
    }

    if (have('wants_newsletter', availableFields)) {
      myForm.appendFieldRowsTo('.row-wants-newsletter', ['wants_newsletter'], {
        row: 'fbx-field space-y-1 donationForm__checkbox',
        checkbox: 'mr-2',
      });
      $('.row-wants-newsletter .fbx-field').each(function () {
        const $field = $(this);
        const $input = $field.find('input[type="checkbox"]');
        const $label = $field.find('label');
        $field.empty().append($input, $label);
      });
    }

    if (have('wants_receipt', availableFields)) {
      myForm.appendFieldRowsTo('.row-wants-receipt', ['wants_receipt'], {
        row: 'fbx-field space-y-1',
        checkbox: 'mr-2',
      });

      if (typeof transformSelectToCheckbox === 'function') {
        transformSelectToCheckbox(
          { id: 'payment_wants_receipt' },
          $(component),
          {
            checkboxLabel: 'Spendenquittung jährlich',
            checkedValue: 'receipt_end_of_year',
          }
        );
      }
    }

    const addressFields = ['address', 'post_code', 'city', 'country'];
    addressFields.forEach(field => {
      if (have(field, availableFields)) {
        myForm.appendFieldRowsTo('.row-address-data', [field]);
      }
    });

    toggleAddressSection(availableFields);

    const $customAmount = $(component).find('#custom_amount');
    function initAmountSync() {
      $(component).on('change', '.amount-group-wrapper input.amount-radio', function () {
        const val = $(this).val();
        setAmount(val);
        $customAmount.val('');
      });

      $customAmount.on('input', function () {
        const v = $(this).val().trim().replace(/[^0-9.]/g, '');
        $(this).val(v);
        $(component).find('.amount-group-wrapper.active input.amount-radio').prop('checked', false);
        if (v) setAmount(v);
      });

      const $checked = $(component).find('.amount-group-wrapper.active input.amount-radio:checked').first();
      if ($checked.length) {
        setAmount($checked.val());
      } else if ($customAmount.val()) {
        setAmount($customAmount.val());
      }
    }
    setTimeout(() => {
      initAmountSync();
    }, 0)


    if (have('payment_method', availableFields)) {
      myForm.appendFieldRowsTo('.payment-methods', ['payment_method']);

      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios(
          { id: 'payment_payment_method' },
          $(component),
          {
            classes: {
              wrapper: 'payment-methods-wrapper',
            },
          }
        );
      }

      const $pmWrapper = $(component).find('.payment-methods');
      $(component).on('change', '[name="payment[payment_method]"]', () => {
        $pmWrapper.find('.radioWrapper').each(function () {
          const $wrap = $(this);
          const $input = $wrap.find('input[type="radio"]').first();
          $wrap.toggleClass('is-selected', $input.is(':checked'));
        });
        togglePaymentFields();
      });

      availableFields.filter(field => field.includes('bank_')).forEach(field => {
        myForm.appendFieldRowsTo('.donation-sepa-fields', [field]);
      });

      const creditCardFields = [
        'credit_card_owner',
        'credit_card_number',
        'credit_card_expiry',
        'credit_card_secure_id',
      ];

      creditCardFields.forEach(field => {
        if (have(field, availableFields)) {
          myForm.appendFieldRowsTo('.donation-creditcard-fields', [field]);
        }
      });

      const creditCardHiddenFields = [
        'credit_card_number_hidden',
        'credit_card_secure_id_hidden',
        'credit_card_expiry_hidden',
        'credit_card_token',
        'credit_card_network',
      ];

      creditCardHiddenFields.forEach(field => {
        if (have(field, availableFields)) {
          myForm.appendFieldRowsTo('.donation-creditcard-hidden', [field]);
        }
      });

      togglePaymentFields();
    }

    const hiddenKeys = [
      'amount',
      'parent_url',
      'success_redirect_url',
      'failure_redirect_url',
      'ip',
      'user_agent',
      'element_hash',
      'donor_covers_the_fee',
      'covered_fee_checksum_amount',
      'donation_custom_field_13071',
      'message',
      'birthday',
    ];
    hiddenKeys.forEach(key => {
      if (have(key, availableFields)) {
        myForm.appendFieldRowsTo('#hidden-fields', [key], { row: 'hidden' });
      }
    });

    toggleAddressSection(availableFields);
  });

  $(component).on('input change', '[name="payment[company_name]"], [name="payment[wants_receipt]"]', () => {
    toggleAddressSection(availableFields);
  });

  myForm.on('fundraisingBox:error', (e) => {
    if (myForm.renderErrors()) {
      console.log(e);
    }
  });

  myForm.on('fundraisingBox:payment', (event, json) => {
    if (myForm.getErrors()) {
      $('#errorMsg').show();
      $.each(myForm.getErrors().current_fields || {}, (key, value) => {
        $('#errorMsg').append(`<div style="max-width: 300px; margin: 20px auto;">${value}</div>`);
      });
      console.log(json);
    }
  });
}