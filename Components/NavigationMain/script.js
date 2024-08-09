import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import $ from 'jquery'
import { buildRefs } from '@/assets/scripts/helpers.js'


export default function (el) {
  const refs = buildRefs(el)
  let navScrolled = false

  const SCROLLED_THRESHOLD = 0
  const BACKGROUND_COLOR_SCROLLED = 'bg-white'

  initNavState()
 
  function initNavState() {
    const currentScrollPos = $(window).scrollTop()

    if (currentScrollPos > SCROLLED_THRESHOLD) {
      setScrolledState()
    } else {
      unsetScrolledState()
    }
  }

  function setScrolledState() {
    if (navScrolled) return
    navScrolled = true
    // Set style classes
    refs.nav.classList.add(BACKGROUND_COLOR_SCROLLED)
    refs.logo.classList.add('hidden')
    refs.logoSecondary.classList.remove('hidden')
  }

  function unsetScrolledState() {
    if (!navScrolled) return
    navScrolled = false
    refs.nav.classList.remove(BACKGROUND_COLOR_SCROLLED)
    refs.logo.classList.remove('hidden')
    refs.logoSecondary.classList.add('hidden')
  }

  function handleScroll() {
    const currentScrollPos = $(window).scrollTop()
    if (currentScrollPos > SCROLLED_THRESHOLD) {
      setScrolledState()
    } else {
      unsetScrolledState()
    }
  }

  $(window).on('scroll', handleScroll)

  const isDesktopMediaQuery = window.matchMedia('(min-width: 1180px)')
  isDesktopMediaQuery.addEventListener('change', onBreakpointChange)

  onBreakpointChange()

  function onBreakpointChange() {
    if (isDesktopMediaQuery.matches) {
      setScrollPaddingTop()
    }
  }

  function setScrollPaddingTop() {
    const scrollPaddingTop = document.getElementById('wpadminbar')
      ? document.getElementById('wpadminbar').offsetHeight
      : 0
    document.documentElement.style.scrollPaddingTop = `${scrollPaddingTop}px`
  }

  // hide / show navigation on scroll
  const showAnim = gsap
    .from('[name="NavigationMain"]', {
      yPercent: -100,
      paused: true,
      duration: 0.4,
      // scrub: 0.5,
    })
    .progress(1)

  ScrollTrigger.create({
    start: 'top top-=100',
    end: 9999,
    markers: false,
    onUpdate: (self) => {
      if (self.direction === -1) {
        showAnim.play()
      } else if (self.direction === 1) {
        showAnim.reverse()
      }
    },
    onRefresh: (self) => {
      // scroll to element id on load
      const url = window.location.href
      const hash = url.substring(url.indexOf('#') + 1)
      if (hash) {
        const element = document.getElementById(hash)
        if (element) {
          element.scrollIntoView()
        }
      }
    },
  })
}

