import $ from 'jquery'
import hashSha256 from './hash'

export default function bindFbxEvents(component, myForm) {

  // Minimal FBX error handling (avoid duplicate inline errors)
  myForm.on('fundraisingBox:error', (e) => {
    // eslint-disable-next-line no-console
    console.log('FBX error event', e)
  })

  myForm.on('fundraisingBox:payment', (event, json = {}) => {
    const $error = $('#errorMsg')
    $error.empty()

    const errs = myForm.getErrors?.()
    const fieldErrors = errs?.current_fields || {}

    if (errs && Object.keys(fieldErrors).length) {
      $error.show()
      $.each(fieldErrors, (key, value) => {
        $error.append(`<div style="max-width: 300px; margin: 20px auto;">${value}</div>`)
      })
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
