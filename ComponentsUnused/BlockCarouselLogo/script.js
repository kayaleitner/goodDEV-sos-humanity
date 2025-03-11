import Swiper from 'swiper'
import {
  Navigation,
  A11y,
  Autoplay,
  Pagination,
  Parallax,
  Scrollbar,
  Mousewheel,
} from 'swiper/modules'
import 'swiper/css/bundle'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import { buildRefs, remToPx } from '@/assets/scripts/helpers.js'

gsap.registerPlugin(ScrollTrigger)

const a11yOptions = {
  prevSlideMessage: 'Previous logo',
  nextSlideMessage: 'Next logo',
  firstSlideMessage: 'This is the first logo',
  lastSlideMessage: 'This is the last logo',
  paginationBulletMessage: 'Go to slide {{index}}',
}

export default (component) => {
  const refs = buildRefs(component)
  const swiper = initSlider(refs)
  return () => swiper.destroy()
}

function initSlider(refs) {
  const carouselOptionsString = refs.slider.dataset.swiper
  const carouselOptions = carouselOptionsString
    ? JSON.parse(carouselOptionsString)
    : {
      loop: true,
      autoplay: false,
      delay: 3000,
      speed: 300,
    }

  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination, Parallax, Scrollbar, Mousewheel],
    a11y: a11yOptions,
    grabCursor: true,
    slidesPerView: 2,
    spaceBetween: remToPx(4),
    loop: carouselOptions.loop,
    speed: carouselOptions.speed,
    mousewheel: {
      enabled: true,
      forceToAxis: true,
    },
    autoplay:
      carouselOptions.autoplay && Number(carouselOptions.delay)
        ? {
          delay: carouselOptions.delay,
        }
        : false,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev,
    },
    breakpoints: {
      780: {
        slidesPerView: 3,
        spaceBetween: remToPx(2),
      },
      1180: {
        slidesPerView: 5,
      },
    },
    on: {
      afterInit: (swiper) => {
        ScrollTrigger.refresh()
      },
    },
  }

  return new Swiper(refs.slider, config)
}
