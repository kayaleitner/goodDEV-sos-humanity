import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

gsap.registerPlugin(ScrollTrigger)

export default (BlockScrollySlides) => {
  const refs = buildRefs(BlockScrollySlides, true)
  const data = getJSON(BlockScrollySlides)
  initScrolly(refs, data)
}

function initScrolly(refs, data) {
  // const { options } = data

  refs.slides.forEach((slide, i) => {
    ScrollTrigger.create({
      trigger: slide,
      start: `top top+=${(i + 1) * 48 + 48}px`,
      endTrigger: slide.parentNode,
      end: 'bottom bottom',
      pin: true,
      pinSpacing: false,
      markers: false,
    })
  })
}
