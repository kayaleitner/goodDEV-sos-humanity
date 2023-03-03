import Swiper, { Navigation, A11y, Autoplay } from 'swiper'
import 'swiper/css/bundle'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (sliderText) {
  const refs = buildRefs(sliderText)
  const data = getJSON(sliderText)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay],
    a11y: options.a11y,
    slidesPerView: 'auto',
    spaceBetween: 0,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    }
    // breakpoints: {
    //   1181: {
    //     slidesPerView: 4,
    //     spaceBetween: 35,
    //   },
    // },
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider, config)
}
