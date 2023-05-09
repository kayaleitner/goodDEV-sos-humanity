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

mm.add('(min-width: 780px)', () => {
  // eslint-disable-next-line no-unused-vars
  const pinner = gsap.timeline({
    scrollTrigger: {
      trigger: '#scrollytelling-inner .wrapper',
      endTrigger: '#scrollytelling',
      start: '-50px top',
      end: 'bottom bottom',
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
    if (i !== 0) {
      tl.from(elem.querySelector('img'), { autoAlpha: 0 }, i)
    }

    if (i !== points.length - 1) {
      tl.to(elem.querySelector('img'), { autoAlpha: 0 }, i + 1)
    }
  })
})
