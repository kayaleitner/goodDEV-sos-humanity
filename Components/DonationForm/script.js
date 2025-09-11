import {
  transformSelectToRadios,
} from './utils/utils'

import initDonationFormValidation from './utils/validation'
import floatingLabel from './utils/floatingLabel'
import initAmountHandlers from './utils/initAmountHandlers'
import setUtmParamsToCustomField from './utils/setUtmParamsToCustomField'
import initAddressSection from './utils/addressSection'
import initPaymentFields from './utils/paymentFields'
import bindFbxEvents from './utils/fbxEvents'
import attachSubmitHandler from './utils/submitHandler'

export default function (component) {
  const $ = window.jQuery
  const $form = $(component).find('#fbx-donation-form')
  const hash = $form.data('fbxFormHash')
  let availableFields
  if (!hash || typeof $form.fundraisingBoxForm !== 'function') return

  const classes = {
    error: 'error-text',
  }
  const defaultSuccessUrlPrams = `?status=successful&fb_transaction_id=%fb_transaction_id%&amount=%amount%&interval=%interval%&nl=%wants_newsletter%`;

  // set the dynamic success_redirect_url hidden field based on a selected interval
  try {
    const $hiddenSuccess = $form.find('#payment_success_redirect_url')
    const oneTime = $form.data('success-redirect-one-time')
    const recurring = $form.data('success-redirect-recurring')
    const applySuccessUrl = () => {
      const intervalVal = $(component).find('input[name="payment[interval]"]:checked').val() || '0'
      const base = intervalVal === '1' ? recurring : oneTime
      if ($hiddenSuccess.length && base) {
        // Append FRBox default success params placeholders so backend can substitute
        $hiddenSuccess.val(`${base}${defaultSuccessUrlPrams}`)
      }
    }
    // initial set and on change
    applySuccessUrl()
    $(component).on('change', 'input[name="payment[interval]"]', applySuccessUrl)
  } catch (e) {
    // no-op
  }
  const myForm = $form.fundraisingBoxForm({
    hash,
    generalErrorElement: '#errorMsg',
    classes,
  })

  const have = (key, available) => available.includes(key)

  myForm.on('fundraisingBox:init', () => {
    const fields = myForm.getFormFields?.() || {}
    availableFields = Object.keys(fields)
    // for debugging
    // console.log(fields)

    // Initialize amount & interval
    initAmountHandlers(component)

    // Read URL parameters for interval and amount
    const queryPrams = new URLSearchParams(window.location.search || '')
    const urlInterval = queryPrams.get('interval')
    const urlAmountRaw = queryPrams.get('amount')

    const isValidInterval = urlInterval === '0' || urlInterval === '1'

    if (isValidInterval) {
      // Apply an interval from URL
      $(`input[name="payment[interval]"][value="${urlInterval}"]`)
        .prop('checked', true)
        .trigger('change')
    } else {
      // Fallback to default interval from markup
      const defaultInterval = $('#payment_interval').data('default-interval')
      $(`input[name="payment[interval]"][value="${defaultInterval}"]`)
        .prop('checked', true)
        .trigger('change')
    }

    // Apply amount from URL if provided
    if (urlAmountRaw !== null && urlAmountRaw !== '') {
      // normalize the amount: keep digits and dot/comma, then unify comma to dot
      const normalized = String(urlAmountRaw).replace(/[^0-9.,]/g, '').replace(',', '.')
      if (normalized) {
        const $activeGroup = $(component).find('.amount-group-wrapper.active')
        // Try to select matching preset radio first
        const $matchRadio = $activeGroup.find(`input.amount-radio[value="${normalized}"]`).first()
        if ($matchRadio.length) {
          $matchRadio.prop('checked', true).trigger('change')
        } else {
          // Otherwise, set a custom amount field for the active interval
          const $custom = $activeGroup.find('input.amount-input').first()
          if ($custom.length) {
            $custom.val(normalized).trigger('input')
          }
        }
      }
    }

    const address = initAddressSection(component)
    address.initOnce()

    // Populate UTM params into custom fields (from sessionStorage 'frb_utm_params')
    setUtmParamsToCustomField(component)

    floatingLabel(myForm)

    if (have('payment_method', availableFields)) {
      myForm.appendFieldRowsTo('.payment-methods', ['payment_method'])

      if (typeof transformSelectToRadios === 'function') {
        transformSelectToRadios(
          { id: 'payment_payment_method' },
          $(component),
          {
            classes: {
              wrapper: 'payment-methods-wrapper',
            },
          }
        )
      }

      const payment = initPaymentFields(component, myForm)
      payment.bindMethodSelectionUI()
    }
    
    // Initialize client-side validation
    if (typeof initDonationFormValidation === 'function') {
      initDonationFormValidation(component, myForm)
    }
  })

  bindFbxEvents(component, myForm)
  attachSubmitHandler(component, myForm)
}
