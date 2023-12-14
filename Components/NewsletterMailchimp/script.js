import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import $ from 'jquery'
import { buildRefs } from '@/assets/scripts/helpers.js'

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
        payload: {},
      }

      $(refs.form)
        .serializeArray()
        .forEach((elem) => {
          theData.payload[elem.name] = elem.value
        })

      $.ajax({
        type: 'POST',
        url: myAjaxVar.ajaxUrl,
        dataType: 'json',
        data: theData,
        success(response) {
          const r = JSON.parse(JSON.stringify(response))
          // TODO: handle response
        },
      })
    })
  }
}
