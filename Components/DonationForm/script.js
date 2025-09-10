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
    row: 'fbx-field-wrapper',
    text: 'placeholder',
    label: 'placeholder-label',
    error: 'error-text',
  }
  const defaultSuccessUrlPrams = `?status=successful&fb_transaction_id=%fb_transaction_id%&amount=%amount%&interval=%interval%&nl=%wants_newsletter%`
  const successUrl = `${window.location.origin}/ddd${defaultSuccessUrlPrams}`
  const myForm = $form.fundraisingBoxForm({
    hash,
    generalErrorElement: '#errorMsg',
    classes,
    successUrl,
  })

  const have = (key, available) => available.includes(key)

  myForm.on('fundraisingBox:init', () => {
    const fields = myForm.getFormFields?.() || {}
    availableFields = Object.keys(fields)
    // console.log(fields)

    // Initialize amount & interval
    initAmountHandlers(component)

    const defaultInterval = $('#payment_interval').data('default-interval')
    $(`input[name="payment[interval]"][value="${defaultInterval}"]`)
      .prop('checked', true)
      .trigger('change')

    const address = initAddressSection(component)
    address.initOnce()

    // Populate UTM params into custom fields (from sessionStorage 'frb_params')
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

    const hiddenKeys = [
      'success_redirect_url',
      'failure_redirect_url',
      'user_agent',
      'element_hash',
    ]
    hiddenKeys.forEach((key) => {
      if (have(key, availableFields)) {
        myForm.appendFieldRowsTo('#hidden-fields', [key], { row: 'hidden' })
      }
    })
    
    // Initialize client-side validation
    if (typeof initDonationFormValidation === 'function') {
      initDonationFormValidation(component, myForm)
    }
  })

  bindFbxEvents(component, myForm)
  attachSubmitHandler(component, myForm)
}
