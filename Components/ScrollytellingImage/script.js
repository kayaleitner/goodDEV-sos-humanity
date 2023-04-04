import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

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
    scrollTrigger: {
      trigger: '#scrollytellingImage',
      start: 'top center',
      end: '200%',
      scrub: true,
      markers: false,
      id: 'points'
    }
  })

  // eslint-disable-next-line no-unused-vars
  const pinner = gsap.timeline({
    scrollTrigger: {
      trigger: '#scrollytellingImage .wrapper',
      start: 'top top',
      end: '200%',
      scrub: true,
      pin: '#scrollytellingImage .wrapper',
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
    tl.from(elem.querySelector('.row'), { autoAlpha: 1, translateY: '100vh' }, i)

    if (i !== points.length - 1) {
      tl.to(elem.querySelector('.row'), { autoAlpha: 1, translateY: '-100vh' }, i + 0.75)
      tl.to(elem.querySelector('img'), { autoAlpha: 0 }, i + 0.75)
    }
  })
})
