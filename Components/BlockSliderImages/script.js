import Swiper from 'swiper'
import {
  Navigation,
  A11y,
  Autoplay,
  Pagination
} from 'swiper/modules'
import 'swiper/css/bundle'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

gsap.registerPlugin(ScrollTrigger)

Swiper.use([Navigation, A11y, Autoplay, Pagination])

export default (sliderImages) => {
  const refs = buildRefs(sliderImages)
  const data = getJSON(sliderImages)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider(refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay],
    a11y: options.a11y,
    roundLengths: true,
    pagination: {
      el: refs.pagination,
      type: 'bullets',
      clickable: true,
    },
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev,
    },
    on: {
      afterInit: () => {
        ScrollTrigger.refresh()
      },
    },
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed,
    }
  }

  return new Swiper(refs.slider, config)
}
