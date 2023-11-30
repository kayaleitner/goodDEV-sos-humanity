import $ from 'jquery'
import 'jquery.easing'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default (statistics) => {
  const refs = buildRefs(statistics, true)

  $(refs.amount).each((index, elem) => {
    ScrollTrigger.create({
      trigger: elem,
      once: true,
      onEnter() {
        animateNumber(elem, elem.dataset.amount, true, elem.dataset?.prefix)
      },
    })
  })
}

function animateNumber(
  amountElem,
  countTo,
  thousandsSeperator = false,
  prefix = ''
) {
  console.log('animate', amountElem, countTo)
  $(amountElem).text('0')

  if (countTo === undefined) {
    return new Error('Missing countTo parameter')
  }

  const parsedCountTo = parseInt(countTo, 10)

  $({ countNum: $(amountElem).text() }).animate(
    {
      countNum: parsedCountTo,
    },
    {
      duration: 2000,
      easing: $.easing.easeInOut,
      step() {
        const number = Math.floor(this.countNum)

        if (thousandsSeperator) {
          $(amountElem).text(prefix + number.toLocaleString('en-US'))
        } else {
          $(amountElem).text(prefix + number)
        }
      },
      complete() {
        const number = this.countNum

        if (thousandsSeperator) {
          $(amountElem).text(prefix + number.toLocaleString('en-US'))
        } else {
          $(amountElem).text(prefix + number)
        }
      },
    }
  )

  return true
}
