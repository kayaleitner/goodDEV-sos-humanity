import Swiper from 'swiper'
import { Navigation, A11y, Autoplay, Pagination } from 'swiper/modules'
import 'swiper/css/bundle'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

gsap.registerPlugin(ScrollTrigger)

export default (sliderText) => {
  const refs = buildRefs(sliderText)
  const data = getJSON(sliderText)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider(refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination],
    a11y: options.a11y,
    slidesPerView: 'auto',
    spaceBetween: 0,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev,
    },
    pagination: {
      el: refs.dots,
      type: 'bullets',
      clickable: true,
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 35,
      },
      1181: {
        slidesPerView: 3,
        spaceBetween: 35,
      },
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
