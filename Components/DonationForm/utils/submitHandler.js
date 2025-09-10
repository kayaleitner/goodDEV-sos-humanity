import $ from 'jquery'

export default function attachSubmitHandler(component, myForm) {
  const $root = $(component)
  const $domForm = $root.find('#fbx-donation-form')

  $('#donation-submit-btn').on('click', (e) => {
    e.preventDefault()

    const hasJqValidate = typeof $.fn.validate === 'function' && $domForm.data('validator')
      ? true
      : typeof $.fn.valid === 'function'

    try {
      if (hasJqValidate) {
        const ok = typeof $domForm.valid === 'function' ? $domForm.valid() : $domForm.validate().form()
        if (!ok) return
      } else if (typeof myForm.valid === 'function') {
        if (!myForm.valid()) return
      }

      if (typeof myForm.submit === 'function') {
        myForm.submit()
      } else if ($domForm.length && typeof $domForm[0].submit === 'function') {
        $domForm[0].submit()
      } else {
        const $error = $('#errorMsg')
        $error.text('Ein unerwarteter Fehler ist aufgetreten. Bitte laden Sie die Seite neu und versuchen Sie es erneut.').show()
      }
    } catch (err) {
      const $error = $('#errorMsg')
      $error.text('Ein Fehler ist aufgetreten. Bitte prüfen Sie Ihre Eingaben und versuchen Sie es erneut.').show()
      // eslint-disable-next-line no-console
      console.error('Donation submit error', err)
    }
  })
}
