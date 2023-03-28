import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

// Fade-in Animation
gsap.registerPlugin(ScrollTrigger)

// Responsive
const mm = gsap.matchMedia()

document.addEventListener('resize', () => {
  ScrollTrigger.refresh()
})

ScrollTrigger.defaults({
  markers: false
})

const points = gsap.utils.toArray('.point')

mm.add('(min-width: 1280px)', () => {
  const tl = gsap.timeline({
    duration: points.length,
    scrollTrigger: {
      trigger: '#scrollytelling',
      start: '-50px center',
      end: '200%',
      scrub: true,
      markers: false,
      id: 'points'
    }
  })

  // eslint-disable-next-line no-unused-vars
  const pinner = gsap.timeline({
    scrollTrigger: {
      trigger: '#scrollytelling .wrapper',
      start: '-50px top',
      end: '300%',
      scrub: true,
      pin: '#scrollytelling .wrapper',
      pinSpacing: true,
      id: 'pinning',
      markers: false
    }
  })

  // eslint-disable-next-line no-unused-vars
  function fullscreenListener () {
    const state =
    document.fullScreen ||
    document.mozFullScreen ||
    document.webkitIsFullScreen

    if (!state) {
      ScrollTrigger.config({ autoRefreshEvents: 'visibilitychange,DOMContentLoaded,load,resize' })
    }
  }

  points.forEach(function (elem, i) {
    gsap.set(elem, { position: 'absolute', top: 0 })

    tl.from(elem.querySelector('img'), { autoAlpha: 0 }, i)
    tl.from(elem.querySelector('article'), { autoAlpha: 0, translateY: 100 }, i)

    if (i !== points.length - 1) {
      tl.to(elem.querySelector('article'), { autoAlpha: 0, translateY: -100 }, i + 0.75)
      tl.to(elem.querySelector('img'), { autoAlpha: 0 }, i + 0.75)
    }
  })
})

// responsive
// const mm = gsap.matchMedia()

// document.addEventListener('resize', () => {
//   ScrollTrigger.refresh()
// })

// mm.add('(min-width: 1080px)', () => {
//   gsap.set('.fade-in',
//     { opacity: 0 }
//   )
//   gsap.set('.move-up',
//     { y: 200 }
//   )
// })

// ScrollTrigger.batch('.fade-in', {
//   onEnter: (elements) => gsap.to(elements, { opacity: 1, stagger: 0.2, duration: 1 }),
//   start: '100px bottom',
//   end: 'top top'
// })

// ScrollTrigger.batch('.move-up', {
//   onEnter: (elements) => gsap.to(elements, { y: 0, stagger: 0.2, duration: 1 }),
//   start: '100px bottom',
//   end: 'top top'
// })
