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
      let intervalVal = '0';

      const $checked = $(component).find('input[name="payment[interval]"]:checked');
      if ($checked.length) {
        intervalVal = $checked.val();
      } else {
        // 2. Fallback: Hidden-Feld auslesen
        const $hiddenInterval = $(component).find('input[name="payment[interval]"][type="hidden"]');
        if ($hiddenInterval.length) {
          intervalVal = $hiddenInterval.val();
        }
      }
      const base = intervalVal === '0' ? oneTime : recurring;
      if ($hiddenSuccess.length && base) {
        $hiddenSuccess.val(`${base}${defaultSuccessUrlPrams}`);
      }
    };
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
