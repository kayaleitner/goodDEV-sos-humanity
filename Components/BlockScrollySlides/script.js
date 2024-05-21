import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { ScrollToPlugin } from 'gsap/ScrollToPlugin'
import { gsap } from 'gsap'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

gsap.registerPlugin(ScrollTrigger)
gsap.registerPlugin(ScrollToPlugin)

export default (BlockScrollySlides) => {
  const refs = buildRefs(BlockScrollySlides, true)
  initScrolly(refs)
}

const initScrolly = (refs) => {
  const mm = gsap.matchMedia()

  mm.add('(min-width: 1280px)', () => {
    let currentSlide = 1

    const setSlide = (i) => {
      currentSlide = i + 1
      refs.indicator[0].innerHTML = `0${currentSlide}--0${refs.slides.length}`
    }

    refs.slides.forEach((slide, i) => {
      ScrollTrigger.create({
        trigger: slide,
        snap: false,
        start: `top top+=${(i + 1) * (36 + window.innerHeight / 40)}px`,
        endTrigger: slide.parentNode,
        end: `bottom-=${(i) * 0}px bottom`,
        pin: true,
        pinSpacing: false,
        markers: false,
        onEnter: () => {
          setSlide(i)
        },
        onEnterBack: () => {
          setSlide(i)
        },
      })
    })

    refs.next[0].addEventListener('click', (e) => {
      if (currentSlide <= refs.slides.length - 1) {
        gsap.to(window, {
          duration: 0.1,
          ease: "power2",
          scrollTo: {
            y: `#scrolly-slide-${currentSlide + 1}`,
            offsetY: -10,
          },
        })
      }
    })
  })

}
