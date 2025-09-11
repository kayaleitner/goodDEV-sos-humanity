import translationDict from './translationDict'

export default function initDonationFormValidation(component, myForm) {
  const $ = window.jQuery
  const $form = $(component).find('#fbx-donation-form')
  if (!$form.length || typeof $.fn.validate !== 'function') return

  // i18n messages
  const lang = (document.documentElement.lang || 'de').slice(0, 2).toLowerCase()

  const t = translationDict[['de', 'en', 'it'].includes(lang) ? lang : 'de']

  // Helper to toggle valid/invalid classes and icons
  const setState = ($el, isValid) => {
    $el.toggleClass('is-valid', !!isValid)
    $el.toggleClass('is-invalid', !isValid)
  }

  const amountMin = 3

  // Available fields detection relies on FBX; fall back to finding fields in DOM
  const availableFields =
    typeof myForm?.getFormFields === 'function'
      ? Object.keys(myForm.getFormFields() || {})
      : []
  const have = (key) =>
    availableFields.includes(key) ||
    $(component).find(`[name="payment[${key}]"]`).length > 0

  // Setup jQuery Validate
  $form.validate({
    ignore: [],
    errorElement: 'span',
    errorClass: 'error-text',
    focusInvalid: false,
    invalidHandler(form, validator) {
      if (!validator.numberOfInvalids()) {
        return
      }
      $('html, body').animate(
        {
          scrollTop: $(validator.errorList[0].element).offset().top - 200,
        },
        300
      )
      validator.errorList[0].element.focus()
    },
    rules: {
      // Core identity
      'payment[salutation]': { required: have('salutation') },
      'payment[first_name]': { required: have('first_name') },
      'payment[last_name]': { required: have('last_name') },
      'payment[email]': { required: have('email'), email: true },
      'payment[amount]': { required: true, number: true, min: amountMin },
      'payment[payment_method]': { required: have('payment_method') },

      // Address and company (conditionally required via setMandatory + visibility)
      'payment[company_name]': {
        required() {
          const $wrap = $(component).find('.payment-company-name')
          return $wrap.is(':visible') && $(component).find('#donate-as-company').is(':checked')
        },
        maxlength: 1000,
      },
      'payment[address]': {
        required() {
          return $(component).find('.payment-address-data').is(':visible')
        },
        maxlength: 300,
      },
      'payment[post_code]': {
        required() {
          return $(component).find('.payment-address-data').is(':visible')
        },
        maxlength: 100,
      },
      'payment[city]': {
        required() {
          return $(component).find('.payment-address-data').is(':visible')
        },
        maxlength: 100,
      },
      'payment[country]': {
        required() {
          return $(component).find('.payment-address-data').is(':visible')
        },
      },
      'payment[bank_account_owner]': {
        required() {
          return $(component).find('.donation-sepa-fields').is(':visible')
        },
      },
      'payment[bank_iban]': {
      required() {
        return $(component).find('.donation-sepa-fields').is(':visible')
      },
    },
    },
    messages: {
      'payment[salutation]': { required: t.salutation },
      'payment[first_name]': { required: t.firstName },
      'payment[last_name]': { required: t.lastName },
      'payment[email]': { required: t.email, email: t.email },
      'payment[amount]': {
        required: t.amount.replace('{0}', amountMin),
        number: t.number,
        min: t.min.replace('{0}', amountMin),
      },
      'payment[payment_method]': { required: t.paymentMethod },

      // Address and company
      'payment[company_name]': {
        required: t.company,
        maxlength: t.maxlength.replace('{0}', 1000),
      },
      'payment[address]': {
        required: t.street,
        maxlength: t.maxlength.replace('{0}', 300),
      },
      'payment[post_code]': {
        required: t.postalCode,
        maxlength: t.maxlength.replace('{0}', 100),
      },
      'payment[city]': {
        required: t.city || t.required,
        maxlength: t.maxlength.replace('{0}', 100),
      },
      'payment[country]': {
        required: t.country,
      },
      'payment[bank_account_owner]': {
        required: t.bankAccountName,
      },
      'payment[bank_iban]': {
        required: t.iban,
      },
    },
    highlight(element) {
      const $el = $(element)
      setState($el, false)
    },
    unhighlight(element) {
      const $el = $(element)
      setState($el, true)
    },
    success(label, element) {
      const $el = $(element)
      setState($el, true)
    },
    errorPlacement(error, element) {
      const $el = $(element)
      const name = $el.attr('name')

      // Try to place the error into our standard wrapper: {fieldId}_wrapper or next to an explicit {fieldId}_error node
      const fieldId = $el.attr('id') || ''
      if (fieldId) {
        // Prefer an explicit error container if present
        const $explicitError = $(`#${fieldId}_error`)
        if ($explicitError.length) {
          $explicitError.empty().append(error)
          return
        }
        // Fallback to the wrapper container
        const $wrapper = $(`#${fieldId}_wrapper`)
        if ($wrapper.length) {
          $wrapper.append(error)
          return
        }
      }

      if (name === 'payment[salutation]') {
        const $salError = $(component).find('#payment_salutation_error')
        if ($salError.length) {
          $salError.empty().append(error)
        } else {
          $(component).find('#payment_salutation_wrapper').append(error)
        }
      } else if (name === 'payment[payment_method]') {
        $(component).find('.payment-methods').append(error)
      } else if (name === 'payment[interval]') {
        const $intervalError = $(component).find('#payment_interval_error')
        if ($intervalError.length) {
          $intervalError.empty().append(error)
        } else {
          $(component).find('#payment_interval').append(error)
        }
      } else if ($el.closest('.fbx-field').length) {
        $el.closest('.fbx-field').append(error)
      } else {
        error.insertAfter($el)
      }
    },
  })
}
