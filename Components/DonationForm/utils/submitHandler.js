import $ from 'jquery'
import translationDict from '@/Components/DonationForm/utils/translationDict.js'

export default function attachSubmitHandler(component, myForm) {
  const $root = $(component)
  const $domForm = $root.find('#fbx-donation-form')

  $('#donation-submit-btn').on('click', (e) => {
    e.preventDefault()

    const hasJqValidate = typeof $.fn.validate === 'function' && $domForm.data('validator')
      ? true
      : typeof $.fn.valid === 'function'

    const lang = (document.documentElement.lang || 'de').slice(0, 2).toLowerCase()

    const t = translationDict[['de', 'en', 'it'].includes(lang) ? lang : 'de']

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
        $error.text(t.globalError).show()
      }
    } catch (err) {
      const $error = $('#errorMsg')
      $error.text(t.globalError).show()
      // eslint-disable-next-line no-console
      console.error('Donation submit error', err)
    }
  })
}
