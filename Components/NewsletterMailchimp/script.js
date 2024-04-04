import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import $ from 'jquery'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export function open() {
  // Hacky way of getting the element
  const el = document.querySelector('flynt-component[name=NewsletterMailchimp]')
  const refs = buildRefs(el)
  el.setAttribute('data-open', true)
  disableBodyScroll(refs.formContainer)
}

export function close() {
  // Hacky way of getting the element
  const el = document.querySelector('flynt-component[name=NewsletterMailchimp]')
  const refs = buildRefs(el)
  el.setAttribute('data-open', false)
  enableBodyScroll(refs.formContainer)
}

export default (el) => {
  const refs = buildRefs(el)
  const data = getJSON(el)
  setEventListeners(refs)

  function setEventListeners() {
    $(refs.close).on('click', () => {
      close()
    })
    // eslint-disable-next-line no-undef
    const { myAjaxVar } = FlyntData // FlyntData is set in inc/assets.php
    refs.form.noValidate = true
    $(refs.form)
      .find('input')
      .each((index, elem) => {
        elem.addEventListener('input', () => {
          if (elem.checkValidity()) {
            $(elem).prev('label').removeClass('hasError')
          } else {
            $(elem).prev('label').addClass('hasError')
          }
        })
      })

    $(refs.form).on('submit', (e) => {
      e.preventDefault()

      $(refs.form)
        .find('input')
        .each((index, elem) => {
          if (elem.checkValidity()) {
            $(elem).prev('label').removeClass('hasError')
          } else {
            $(elem).prev('label').addClass('hasError')
          }
        })

      if (!refs.form.checkValidity()) {
        e.stopImmediatePropagation()
        return
      }

      // eslint-disable-next-line prefer-const
      let theData = {
        action: 'subscribe_to_mailchimp_list',
        nonce: myAjaxVar.nonce,
        payload: {
          merge_fields: {},
        },
      }

      $(refs.form)
        .serializeArray()
        .forEach((elem) => {
          if (elem.name === 'JOB' || elem.name === 'ORG') {
            theData.payload.merge_fields[elem.name] = elem.value
          } else {
            theData.payload[elem.name] = elem.value
          }
        })

      $.ajax({
        type: 'POST',
        url: myAjaxVar.ajaxUrl,
        dataType: 'json',
        data: theData,
        success(response) {
          const r = JSON.parse(JSON.stringify(response))
          // TODO: handle response
          if (r.success) {
            handleSuccess(r)
          } else {
            handleError(r)
          }
        },
      })
    })

    $(refs.btnCloseError).on('click', function () {
      $(this).closest('[data-ref$=Bar]').fadeOut()
    })

    $(refs.btnCloseSuccess).on('click', function () {
      $(this).closest('[data-ref$=Bar]').fadeOut()
    })
  }

  function handleSuccess() {
    gtag('event', 'subscribe')
    refs.form.reset()
    showSuccess(data.msgSuccess)
  }

  function handleError(r) {
    let message
    if (r.error_body) {
      message = r.error_body.detail
    } else if (r.error_message) {
      message = r.error_message
    }

    showError(message)
  }

  function showSuccess(message) {
    $(refs.successText).text(message)
    $(refs.successBar).fadeIn(400, () => {
      setTimeout(() => {
        $(refs.successBar).fadeOut()
      }, 4000)
    })
  }

  function showError(message) {
    $(refs.errorText).text(message)
    $(refs.errorBar).fadeIn(400, () => {
      setTimeout(() => {
        $(refs.errorBar).fadeOut()
      }, 4000)
    })
  }
}
