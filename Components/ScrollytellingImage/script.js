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

const points = gsap.utils.toArray('.panel')

const height = 100 * points.length

mm.add('(min-width: 1280px)', () => {
  // eslint-disable-next-line no-unused-vars
  const pinner = gsap.timeline({
    scrollTrigger: {
      trigger: '#scrollytellingImage .wrapper',
      start: 'top top',
      end: '+=' + height + '%',
      scrub: true,
      pin: true,
      id: 'pinning',
      markers: false
    }
  })

  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: '#scrollytellingImage',
      start: 'top bottom',
      end: '+=' + height + '%',
      scrub: true,
      id: 'points'
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

    tl.from(elem.querySelector('img'), { opacity: 0, display: 'none' }, i)
    tl.from(elem.querySelector('.row'), { opacity: 0, display: 'none', translateY: '75vh' }, i)

    if (i !== points.length - 1) {
      tl.to(elem.querySelector('.row'), { opacity: 1, display: 'block', translateY: '-75vh' }, i + 0.75)
      tl.to(elem.querySelector('img'), { opacity: 1, display: 'block' }, i + 0.75)
    }
  })
})
