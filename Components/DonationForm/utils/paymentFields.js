import $ from 'jquery'
import setMandatory from './setMandatory'

export default function initPaymentFields(component, myForm) {
  const $root = $(component)

  // Wallet pay (Apple/Google Pay via Stripe) is implemented as a one-time
  // PaymentIntent in the FRBox SDK and does not support recurring intervals.
  // → only offer these methods for one-time donations (interval '0').
  const walletMethods = ['stripe_apple_pay', 'stripe_google_pay']

  function getInterval() {
    const $checked = $root.find('input[name="payment[interval]"]:checked')
    if ($checked.length) return $checked.val()
    const $hidden = $root.find('input[name="payment[interval]"][type="hidden"]')
    return $hidden.length ? $hidden.val() : '0'
  }

  function applyWalletAvailability() {
    const isRecurring = getInterval() !== '0'
    const $pmWrapper = $root.find('.payment-methods')
    let unselectedWallet = false

    walletMethods.forEach((method) => {
      const $wrap = $pmWrapper.find(`[data-payment-method="${method}"]`)
      if (!$wrap.length) return
      const $input = $wrap.find('input[type="radio"]').first()

      if (isRecurring) {
        if ($input.is(':checked')) {
          $input.prop('checked', false)
          $wrap.removeClass('is-selected')
          unselectedWallet = true
        }
        $wrap.stop(true, true).hide()
      } else {
        $wrap.stop(true, true).show()
      }
    })

    // If we just removed a selected wallet method, refresh dependent fields/infos
    if (unselectedWallet) togglePaymentFields()
  }

  function togglePaymentFields() {
    const val = $root.find('[name="payment[payment_method]"]:checked').val()
    const sepaValues = ['sepa_direct_debit', 'wikando_direct_debit', 'bank']
    const creditCardValues = ['creditcard', 'credit_card', 'stripe_credit_card']
    const paypalValues = ['paypal']
    const applePayValues = ['stripe_apple_pay']
    const googlePayValues = ['stripe_google_pay']

    const $sepaFields = $root.find('.donation-sepa-fields')
    const $creditCardFields = $root.find('.donation-creditcard-fields')

    const $paypalInfo = $root.find('.donation-paypal-info')
    const $applePayInfo = $root.find('.donation-applepay-info')
    const $googlePayInfo = $root.find('.donation-googlepay-info')

    // Hide all info blocks initially
    const hideAllInfos = () => {
      $paypalInfo.stop(true, true).slideUp(200)
      $applePayInfo.stop(true, true).slideUp(200)
      $googlePayInfo.stop(true, true).slideUp(200)
    }

    if (sepaValues.includes(val)) {
      hideAllInfos()
      $sepaFields.stop(true, true).slideDown(300, () => setMandatory($sepaFields, true))
      $creditCardFields.stop(true, true).slideUp(300, () => setMandatory($creditCardFields, false))
      myForm?.creditCardReset?.()
    } else if (creditCardValues.includes(val)) {
      hideAllInfos()
      $creditCardFields.stop(true, true).slideDown(300, () => setMandatory($creditCardFields, true))
      $sepaFields.stop(true, true).slideUp(300, () => setMandatory($sepaFields, false))
      myForm?.bankAccountReset?.()
    } else if (paypalValues.includes(val)) {
      $sepaFields.stop(true, true).slideUp(300, () => setMandatory($sepaFields, false))
      $creditCardFields.stop(true, true).slideUp(300, () => setMandatory($creditCardFields, false))
      hideAllInfos()
      $paypalInfo.stop(true, true).slideDown(200)
      myForm?.creditCardReset?.()
      myForm?.bankAccountReset?.()
    } else if (applePayValues.includes(val)) {
      $sepaFields.stop(true, true).slideUp(300, () => setMandatory($sepaFields, false))
      $creditCardFields.stop(true, true).slideUp(300, () => setMandatory($creditCardFields, false))
      hideAllInfos()
      $applePayInfo.stop(true, true).slideDown(200)
      myForm?.creditCardReset?.()
      myForm?.bankAccountReset?.()
    } else if (googlePayValues.includes(val)) {
      $sepaFields.stop(true, true).slideUp(300, () => setMandatory($sepaFields, false))
      $creditCardFields.stop(true, true).slideUp(300, () => setMandatory($creditCardFields, false))
      hideAllInfos()
      $googlePayInfo.stop(true, true).slideDown(200)
      myForm?.creditCardReset?.()
      myForm?.bankAccountReset?.()
    } else {
      hideAllInfos()
      $sepaFields.stop(true, true).slideUp(300, () => setMandatory($sepaFields, false))
      $creditCardFields.stop(true, true).slideUp(300, () => setMandatory($creditCardFields, false))
      myForm?.creditCardReset?.()
      myForm?.bankAccountReset?.()
    }
  }

  function bindMethodSelectionUI() {
    const $pmWrapper = $root.find('.payment-methods')
    $root.on('change', '[name="payment[payment_method]"]', () => {
      $pmWrapper.find('.radioWrapper').each(function () {
        const $wrap = $(this)
        const $input = $wrap.find('input[type="radio"]').first()
        $wrap.toggleClass('is-selected', $input.is(':checked'))
      })
      togglePaymentFields()
    })

    // Hide Apple/Google Pay for recurring donations and react to interval changes
    $root.on('change', 'input[name="payment[interval]"]', applyWalletAvailability)
    applyWalletAvailability()

    togglePaymentFields()
  }

  return { togglePaymentFields, bindMethodSelectionUI, applyWalletAvailability }
}
