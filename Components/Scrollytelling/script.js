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
      trigger: '#scrollytelling .wrapper',
      start: '-50px top',
      end: '+=' + height + '%',
      scrub: true,
      pin: true,
      id: 'pinning',
      markers: false
    }
  })

  const tl = gsap.timeline({
    duration: points.length,
    scrollTrigger: {
      trigger: '#scrollytelling',
      start: '-50px center',
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

    tl.from(elem.querySelector('img'), { opacity: 0, duration: 0.5, ease: 'power1.inOut' }, i)
    tl.from(elem.querySelector('.row'), { opacity: 1, translateY: '100vh' }, i)

    if (i !== points.length - 1) {
      tl.to(elem.querySelector('.row'), { opacity: 1, translateY: '-100vh' }, i + 0.5)
      tl.to(elem.querySelector('img'), { opacity: 0, duration: 0.5, ease: 'power1.inOut' }, i + 0.5)
    }
  })
})
