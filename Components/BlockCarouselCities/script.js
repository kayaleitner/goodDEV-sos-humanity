import Swiper, { Navigation, A11y, Autoplay, Pagination, Parallax, Scrollbar } from 'swiper'
import 'swiper/css/bundle'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

gsap.registerPlugin(ScrollTrigger)

export default (carouselCities) => {
  const refs = buildRefs(carouselCities)
  const data = getJSON(carouselCities)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider(refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination, Parallax, Scrollbar],
    a11y: options.a11y,
    slidesPerView: 2,
    autoHeight: true,
    spaceBetween: 15,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev,
    },
    scrollbar: {
      el: refs.indicator,
      draggable: true,
      dragSize: 100
    },
    breakpoints: {
      980: {
        slidesPerView: 'auto',
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
