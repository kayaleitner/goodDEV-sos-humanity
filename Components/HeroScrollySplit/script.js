import Swiper, { Navigation, A11y, Autoplay } from 'swiper'
import 'swiper/css/bundle'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'
import gsap from 'gsap'

export default function (heroScrollySplit) {
  const refs = buildRefs(heroScrollySplit, true)
  const data = getJSON(heroScrollySplit)
  const timeline = gsap.timeline({
    scrollTrigger: {
      trigger: refs.section,
      pin: true,
      start: 'top top',
      end: 'bottom+=50% bottom',
      scrub: true,
      // snap: 1,
      // markers: true,
      snap: {
        snapTo: 1,
        duration: { min: 0.01, max: 0.3 },
        delay: 0,
        ease: 'power0.inOut',
        inertia: true
      }
    }
  })
  timeline
    .from(refs.text_2, {
      opacity: 1,
      duration: 0.2
    })
    .to(refs.left, {
      y: '100vh'
    }, 0)
    .to(refs.right, {
      y: '-100vh'
    }, 0)
    .to(refs.text_2, {
      opacity: 0,
      duration: 0.2
    }, 0)
    .to(refs.text_3, {
      opacity: 1,
      duration: 0.2
    }, 0)
    .to(refs.text_1, {
      opacity: 1,
      duration: 0.2
    }, 0)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay],
    a11y: options.a11y,
    roundLengths: true,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    }
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider, config)
}
