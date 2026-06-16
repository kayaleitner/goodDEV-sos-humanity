import $ from 'jquery'
import hashSha256 from './hash'
import translationDict from './translationDict'

export default function bindFbxEvents(component, myForm) {

  const lang = (document.documentElement.lang || 'de').slice(0, 2).toLowerCase()
  const t = translationDict[['de', 'en', 'it'].includes(lang) ? lang : 'de']

  // Minimal FBX error handling (avoid duplicate inline errors)
  myForm.on('fundraisingBox:error', (e) => {
    // eslint-disable-next-line no-console
    console.log('FBX error event', e)
  })

  // Wallet pay readiness: expose availability and allow UI updates
  myForm.on('fundraisingBox:walletPayReady', (_event, paymentMethods = {}) => {
    try {
      const $root = $(component)

      // Add data flags on root for easy CSS/JS hooks
      const canApple = !!paymentMethods?.stripe_apple_pay?.can_make_payment
      const canGoogle = !!paymentMethods?.stripe_google_pay?.can_make_payment

      $root.attr('data-wallet-apple', canApple ? '1' : '0')
      $root.attr('data-wallet-google', canGoogle ? '1' : '0')

      // Toggle optional logo elements if they exist
      const $appleLogo = $root.find('[data-wallet-logo="apple"]')
      const $googleLogo = $root.find('[data-wallet-logo="google"]')
      if ($appleLogo.length) $appleLogo.toggle(!!canApple)
      if ($googleLogo.length) $googleLogo.toggle(!!canGoogle)

      // Hide any loading overlays/spinners that wait for wallet readiness
      $root.find('[data-wallet-loading], .wallet-loading, .wallet-overlay').hide()

      // Also emit a DOM event so themes can react without jQuery
      const detail = { paymentMethods, canApple, canGoogle }
      $root.get(0)?.dispatchEvent(new CustomEvent('walletPayReady', { detail }))
    } catch (err) {
      // eslint-disable-next-line no-console
      console.warn('walletPayReady handler error', err)
    }
  })
  myForm.on('fundraisingBox:payment', (event, json = {}) => {
    const $error = $('#errorMsg')
    $error.empty()

    const errs = myForm.getErrors?.()
    if (errs) {
      $error.show()
      $error.text(t.errorMsg || 'Es sind leider Fehler aufgetreten! Bitte überprüfe deine Formular Eingaben.')
      const fieldErrors = errs.current_fields || {}
      const firstFieldKey = Object.keys(fieldErrors)[0] || null

      if (firstFieldKey && fieldErrors[firstFieldKey]) {
        const $firstErrorEl = $(`#payment_${firstFieldKey}_error`)
        if ($firstErrorEl.length && $firstErrorEl.is(':visible')) {
          $('html, body').animate({ scrollTop: $firstErrorEl.offset().top - 200 }, 400)
        }
      }
      return
    }

    if (json.payment_status === 'success' || json.payment_status === 'awaiting') {
      $error.hide()

      const salutation = $('input[name="payment[salutation]"]:checked').val() || ''
      const title = $('[name="payment[title]"]').val() || ''
      const firstName = $('input[name="payment[first_name]"]').val() || ''
      const lastName = $('input[name="payment[last_name]"]').val() || ''
      const em = $('input[name="payment[email]"]').val() || ''

      hashSha256(em)
        .then((hex) => {
          sessionStorage.setItem('Anrede', salutation)
          sessionStorage.setItem('Titel', title)
          sessionStorage.setItem('Vorname', firstName)
          sessionStorage.setItem('Nachname', lastName)
          sessionStorage.setItem('em', hex)
        })
        .catch(() => {
          sessionStorage.setItem('em_raw', em)
        })
        .finally(() => {
          window.setTimeout(() => {
            localStorage.removeItem('frb_utm_params')
          }, 0)
        })
    } else {
      $error.hide()
    }
  })
}
