import $ from 'jquery'
import { buildRefs } from '@/assets/scripts/helpers.js'

export default (el) => {
  const refs = buildRefs(el)

  // eslint-disable-next-line no-undef
  const { myAjaxVar } = FlyntData // FlyntData is set in inc/assets.php

  refs.form.noValidate = true
  $(refs.form)
    .find('input')
    .each((index, elem) => {
      // console.log(elem)
      elem.addEventListener('input', () => {
        if (elem.checkValidity()) {
          $(elem).prev('label').removeClass('hasError')
        } else {
          $(elem).prev('label').addClass('hasError')
        }
      })
    })

  $(refs.form).on('submit', (e) => {
    console.log('submit!')
    e.preventDefault()
    console.log($(refs.form).serializeArray())

    $(refs.form)
      .find('input')
      .each((index, elem) => {
        if (elem.checkValidity()) {
          $(elem).prev('label').removeClass('hasError')
        } else {
          console.log($(elem).attr('name'), 'failed')
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

    console.log(theData)

    $.ajax({
      type: 'POST',
      url: myAjaxVar.ajaxUrl,
      dataType: 'json',
      data: theData,
      success(response) {
        console.log(response)
        const r = JSON.parse(JSON.stringify(response))
        console.log(r)

        // TODO: handle response
      },
    })
  })
}
