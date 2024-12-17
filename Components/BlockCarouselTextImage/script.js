import Swiper from 'swiper'
import {
  Navigation,
  A11y,
  Autoplay,
  Pagination,
  EffectFade,
} from 'swiper/modules'
import 'swiper/css/bundle'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import { buildRefs, fadeElements } from '@/assets/scripts/helpers.js'
import SwipeListener from 'swipe-listener'

gsap.registerPlugin(ScrollTrigger)

const a11yOptions = {
  prevSlideMessage: 'Previous slide',
  nextSlideMessage: 'Next slide',
  firstSlideMessage: 'This is the first slide',
  lastSlideMessage: 'This is the last slide',
  paginationBulletMessage: 'Go to slide {{index}}',
}

export default (component) => {
  const refs = buildRefs(component)
  const swiper = initSlider(refs)
  initTextSwiper(refs, swiper)
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
  const autoplayProgress = refs.autoplayProgress
  const slideCounter = refs.slideCounter

  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination, EffectFade],
    a11y: a11yOptions,
    grabCursor: true,
    loop: false,
    effect: 'fade',
    fadeEffect: {
      crossFade: false,
    },
    // speed: carouselOptions.speed,
    autoplay:
      carouselOptions.autoplay && Number(carouselOptions.delay)
        ? {
            delay: Number(carouselOptions.delay),
          }
        : false,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev,
    },
    pagination: {
      el: refs.pagination,
      type: 'bullets',
      clickable: true,
    },
    on: {
      // Swiper JS has a bug that when autoplay is enabled the activeIndex stays on the last slide.
      // To make the counter accurate we grab the position from the active pagination index.
      slideChangeTransitionStart: (swiper) => {
        if (carouselOptions.autoplay) {
          const realActiveIndex = Array.from(refs.pagination.children).indexOf(
            refs.pagination.querySelector('.swiper-pagination-bullet-active')
          )

          slideCounter.textContent =
            realActiveIndex + 1 + '/' + swiper.slides.length
        }
      },
      autoplayTimeLeft(swiper, timeLeft, percentage) {
        autoplayProgress.style.setProperty('--progress', 1 - percentage)
      },
      slideNextTransitionStart() {
        fadeElements(refs, 'textFader')
      },
      slidePrevTransitionStart() {
        fadeElements(refs, 'textFader')
      },
    },
  }
  return new Swiper(refs.slider, config)
}

function initTextSwiper(refs, swiper) {
  const textSwiper = refs.textFader
  const textSwipeListener = SwipeListener(textSwiper)

  textSwiper.addEventListener('swipe', function (e) {
    var directions = e.detail.directions

    if (directions.left) {
      swiper.slideNext()
    }

    if (directions.right) {
      swiper.slidePrev()
    }
  })
  textSwiper.addEventListener('mouseover', function (e) {
    textSwiper.style.cursor = 'grab'
  })
  textSwiper.addEventListener('mousedown', function (e) {
    textSwiper.style.cursor = 'grabbing'
  })
  textSwiper.addEventListener('mouseup', function (e) {
    textSwiper.style.cursor = 'grab'
  })
}
