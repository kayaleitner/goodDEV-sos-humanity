import {
  transformSelectToRadios,
  transformSelectToCheckbox,
} from './utils/utils'

export default function (component) {
  const $ = window.jQuery
  const $form = $(component).find('#fbx-donation-form')
  const hash = $form.data('fbxFormHash')
  let availableFields
  if (!hash || typeof $form.fundraisingBoxForm !== 'function') return

  const classes = {
    row: 'fbx-field space-y-1',
    label: 'block text-sm font-medium text-gray-700 mb-1',
    text: 'w-full px-3 py-2',
    select: 'w-full px-3 py-2',
    checkbox: 'mr-2',
    radio: 'mr-2',
    error: 'text-sm red mt-1',
  }

  const myForm = $form.fundraisingBoxForm({
    hash,
    classes,
    creditCardFieldTemplate: 'payment_credit_card_owner',
  })

  const have = (key, available) => available.includes(key)

  function toggleAddressSection(available) {
    const hasCompany =
      have('company_name', available) &&
      ($(component).find('[name="payment[company_name]"]').val() || '').trim()
        .length > 0
    const wantsReceipt =
      have('wants_receipt', available) &&
      $(component).find('[name="payment[wants_receipt]"]').is(':checked')

    const $addressWrapper = $(component).find('.row-address-data-wrapper')
    if (hasCompany || wantsReceipt) {
      $addressWrapper.stop(true, true).removeClass('hidden').slideDown(400)
    } else {
      $addressWrapper.stop(true, true).slideUp(400, function () {
        $(this).addClass('hidden')
      })
    }
  }

  // Smoothly toggle SEPA/credit card fields
  function togglePaymentFields() {
    const val = $(component)
      .find('[name="payment[payment_method]"]:checked')
      .val()
    const sepaValues = ['sepa', 'wikando_direct_debit', 'bank']
    const ccValues = ['creditcard', 'credit_card', 'stripe_credit_card']

    const $sepa = $(component).find('#sepa-fields')
    const $cc = $(component).find('#creditcard-fields')

    if (sepaValues.includes(val)) {
      $sepa.stop(true, true).removeClass('hidden').slideDown(300)
      $cc.stop(true, true).slideUp(300, function () {
        $(this).addClass('hidden')
      })
    } else if (ccValues.includes(val)) {
      $cc.stop(true, true).removeClass('hidden').slideDown(300)
      $sepa.stop(true, true).slideUp(300, function () {
        $(this).addClass('hidden')
      })
    } else {
      // For wallet methods etc., hide both
      $sepa.stop(true, true).slideUp(300, function () {
        $(this).addClass('hidden')
      })
      $cc.stop(true, true).slideUp(300, function () {
        $(this).addClass('hidden')
      })
    }
  }

  // Set the hidden payment[amount] field value
  function setAmount(value) {
    const $hiddenAmount = $(component).find('[name="payment[amount]"]')
    if ($hiddenAmount.length) {
      $hiddenAmount.val(value)
    }
  }

  myForm.on('fundraisingBox:init', () => {
    const fields = myForm.getFormFields?.() || {}
    availableFields = Object.keys(fields)
    // console.log('fields', fields)

    // Intervall
    if (have('interval', availableFields)) {
      myForm.appendFieldRowsTo('.donationForm__interval', ['interval'], {
        row: 'text-center',
      })

      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios({ id: 'payment_interval' }, $(component), {
          classes: {
            wrapper: 'donationForm__interval_wrapper',
          },
        })
      }
    }
    const defaultInterval = $('#payment_interval_wrapper')
      .closest('.donationForm__interval')
      .data('default-interval')
    $(`input[name="payment[interval]"][value="${defaultInterval}"]`)
      .prop('checked', true)
      .trigger('change')

    $('input[name="payment[interval]"]').on('change', function () {
      const interval = $(this).val()
      $('.amount-group-wrapper').removeClass('active').hide()
      const $activeGroup = $(
        `.amount-group-wrapper[data-interval="${interval}"]`
      )
        .addClass('active')
        .show()
      // After switching interval, prefer a checked preset in the new group; otherwise keep custom amount if present
      const $custom = $(component).find('#custom_amount')
      const customVal = ($custom.val() || '').trim()
      if (!customVal) {
        const $defaultRadio = $activeGroup
          .find('input.amount-radio:checked')
          .first()
        if ($defaultRadio.length) {
          setAmount($defaultRadio.val())
        }
      } else {
        // If user has custom value, keep it authoritative
        setAmount(customVal)
        $activeGroup.find('input.amount-radio').prop('checked', false)
      }
    })

    const personFields = [
      'first_name',
      'last_name',
      'company_name',
      'email',
      'phone',
    ]

    personFields.forEach((field, index) => {
      if (!have(field, availableFields)) return
      myForm.appendFieldRowsTo('.row-person-data', [field])
      const $lastField = $('.row-person-data > div:last-child')
      if (index === 2) {
        $lastField.addClass('col-span-1 sm:col-span-2')
      } else {
        $lastField.addClass('col-span-1')
      }
    })

    // Persönliche Daten
    if (have('salutation', availableFields)) {
      myForm.appendFieldRowsTo('#row-salutation', ['salutation'], {
        row: 'fbx-field space-y-1',
        select: 'w-full px-3 py-2',
      })
    }
    if (have('wants_newsletter', availableFields)) {
      myForm.appendFieldRowsTo('.row-wants-newsletter', ['wants_newsletter'], {
        row: 'fbx-field space-y-1 donationForm__checkbox',
        checkbox: 'mr-2',
      })
      // Reihenfolge von input und label umdrehen
      $('.row-wants-newsletter .fbx-field').each(function () {
        const $field = $(this)
        const $input = $field.find('input[type="checkbox"]')
        const $label = $field.find('label')
        $field.empty().append($input, $label) // Input vor Label setzen
      })
    }

    if (have('wants_receipt', availableFields)) {
      myForm.appendFieldRowsTo('.row-wants-receipt', ['wants_receipt'], {
        row: 'fbx-field space-y-1',
        checkbox: 'mr-2',
      })

      if (typeof transformSelectToCheckbox === 'function') {
        transformSelectToCheckbox(
          { id: 'payment_wants_receipt' },
          $(component),
          {
            checkboxLabel: 'Spendenquittung jährlich',
            checkedValue: 'receipt_end_of_year',
          }
        )
      }
    }

    // Address
    const addressFields = ['address', 'post_code', 'city', 'country']
    addressFields.forEach((field) => {
      myForm.appendFieldRowsTo('.row-address-data', [field])
    })

    toggleAddressSection(availableFields)

    // Amount sync: preset radios and custom input -> hidden payment[amount]
    const $customAmount = $(component).find('#custom_amount')
    function initAmountSync() {
      // When a preset is clicked, set value and clear custom
      $(component).on(
        'change',
        '.amount-group-wrapper input.amount-radio',
        function () {
          const val = $(this).val()
          setAmount(val)
          $customAmount.val('')
        }
      )
      // When custom is typed, deselect all radios and set amount
      $customAmount.on('input', function () {
        const v = (this.value || '').trim()
        $(component)
          .find('.amount-group-wrapper.active input.amount-radio')
          .prop('checked', false)
        if (v) setAmount(v)
      })
      // On first init, prefer a checked radio, else custom
      const $checked = $(component)
        .find('.amount-group-wrapper.active input.amount-radio:checked')
        .first()
      if ($checked.length) {
        setAmount($checked.val())
      } else if ($customAmount.val()) {
        setAmount($customAmount.val())
      }
    }
    initAmountSync()

    // payment methods
    if (have('payment_method', availableFields)) {
      myForm.appendFieldRowsTo('#payment-methods', ['payment_method'], {
        row: 'fbx-field space-y-1',
        radio: 'mr-2',
      })
      // Force radios for payment_method if SDK rendered a select
      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios(
          { id: 'payment_payment_method' },
          $(component),
          {
            classes: {
              wrapper: 'payment-methods-wrapper',
              radio: 'mr-2',
              label: 'ml-2',
            },
          }
        )
      }

      // Enhance payment methods: add icons and selected styling
      const $pmWrapper = $(component).find('#payment-methods')
      $pmWrapper.find('.radioWrapper').each(function () {
        const $wrap = $(this)
        const $input = $wrap.find('input[type="radio"]').first()
        const $label = $wrap.find('label').first()
        if (!$input.length || !$label.length) return
        const val = $input.val()
        const iconClass = `pm-icon pm-icon--${val}`
        // Prepend icon span if not present
        if ($label.find('.pm-icon').length === 0) {
          $label.prepend($('<span>', { class: iconClass }))
        } else {
          $label.find('.pm-icon').attr('class', iconClass)
        }
        // Mark selected wrapper
        $wrap.toggleClass('is-selected', $input.is(':checked'))
      })
      $(component).on('change', '[name="payment[payment_method]"]', () => {
        $pmWrapper.find('.radioWrapper').each(function () {
          const $wrap = $(this)
          const $input = $wrap.find('input[type="radio"]').first()
          $wrap.toggleClass('is-selected', $input.is(':checked'))
        })
      })

      availableFields
        .filter((field) => field.includes('bank_'))
        .forEach((field) => {
          myForm.appendFieldRowsTo('#sepa-fields', [field], {
            row: 'fbx-field space-y-1',
          })
        })

      availableFields
        .filter((field) => field.includes('credit_card'))
        .forEach((field) => {
          myForm.appendFieldRowsTo('#creditcard-fields', [field], {
            row: 'fbx-field space-y-1',
          })
        })

      // Initialize payment field visibility smoothly
      togglePaymentFields()
      $(component).on(
        'change',
        '[name="payment[payment_method]"]',
        togglePaymentFields
      )
    }

    // Versteckte Felder
    ;[
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
    ].forEach((key) => {
      if (have(key, availableFields)) {
        myForm.appendFieldRowsTo('#hidden-fields', [key], { row: 'hidden' })
      }
    })

    toggleAddressSection(availableFields)
  })

  // Adresssichtbarkeit bei Änderung
  $(component).on(
    'input change',
    '[name="payment[company_name]"], [name="payment[wants_receipt]"]',
    () => {
      toggleAddressSection(availableFields)
    }
  )

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

  myForm.on('fundraisingBox:error', (e) => {
    if (myForm.renderErrors()) {
      console.log(e)
    }
  })
  myForm.on('fundraisingBox:payment', (event, json) => {
    if (myForm.getErrors()) {
      $('#errorMsg').show()
      $.each(myForm.getErrors().current_fields, (key, value) => {
        $('#errorMsg').append(
          `<div style="max-width: 300px; margin: 20px auto;">${value}</div>`
        )
      })
      console.log(json)
    }
  })
}
