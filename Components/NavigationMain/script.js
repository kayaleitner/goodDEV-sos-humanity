import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import $ from 'jquery'
import { buildRefs } from '@/assets/scripts/helpers.js'
import { open as openNewsletter } from '../NewsletterMailchimp/script'

export default function (el) {
  const refs = buildRefs(el)
  let navScrolled = false
  initNavState()

  function initNavState() {
    const currentScrollPos = $(window).scrollTop()

    if (currentScrollPos > 0) {
      setScrolledState()
    } else {
      unsetScrolledState()
    }
  }

  function setScrolledState() {
    if (navScrolled) return
    navScrolled = true
    // Set style classes
    refs.nav.classList.remove('text-bgColor')
    refs.nav.classList.add('bg-bgColor')
    refs.logo.classList.add('hidden')
    refs.logoDark.classList.remove('hidden')
    $('.after-marker', refs.nav).addClass('after-marker--dark')
    const $button = $('.button--outlineWhite', refs.nav)
    $button
      // prevent transitions on style change, we want instant switch
      .addClass('!transition-none')
      .removeClass('button--outlineWhite')
      .addClass('button--accent')

    // re-add transitions for hover after the switch is complete (100ms is arbitraty)
    setTimeout(function () {
      $button.removeClass('!transition-none')
    }, 100)
  }

  function unsetScrolledState() {
    if (!navScrolled) return
    navScrolled = false
    // Set style classes
    refs.nav.classList.remove('bg-bgColor')
    if (!refs.nav.dataset.onLight) {
      refs.nav.classList.add('text-bgColor')
      refs.logoDark.classList.add('hidden')
      refs.logo.classList.remove('hidden')
      $('.after-marker', refs.nav).removeClass('after-marker--dark')

      const $button = $('.button--accent', refs.nav)
      $button
        .addClass('!transition-none')
        .removeClass('button--accent')
        .addClass('button--outlineWhite')
      setTimeout(function () {
        $button.removeClass('!transition-none')
      }, 100)
    }
  }

  function handleScroll() {
    const currentScrollPos = $(window).scrollTop()
    if (currentScrollPos > 0) {
      setScrolledState()
    } else {
      unsetScrolledState()
    }
  }

  $(window).on('scroll', handleScroll)

  const navigationHeight =
    parseInt(
      window.getComputedStyle(el).getPropertyValue('--navigation-height')
    ) || 0

  const isDesktopMediaQuery = window.matchMedia('(min-width: 1024px)')
  isDesktopMediaQuery.addEventListener('change', onBreakpointChange)

  onBreakpointChange()

  function onBreakpointChange() {
    if (isDesktopMediaQuery.matches) {
      setScrollPaddingTop()
    }
  }

  function setScrollPaddingTop() {
    const scrollPaddingTop = document.getElementById('wpadminbar')
      ? navigationHeight + document.getElementById('wpadminbar').offsetHeight
      : navigationHeight
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
    start: 'center top-=100',
    endTrigger: '.pageWrapper',
    end: 'bottom bottom',
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

  refs.cta.addEventListener('click', (e) => {
    if (e.target.getAttribute('href') === '#') {
      // Open newsletter form
      e.preventDefault()
      openNewsletter()
    }

    // link behaves as default otherwise
  })


  // // hide/show cta on scroll
  // const showCtaAnim = gsap
  //   .from('#ctaMain', {
  //     yPercent: 300,
  //     paused: true,
  //     duration: 0.4,
  //     // scrub: 0.5,
  //   })
  //   .progress(1)
  // ScrollTrigger.create({
  //   start: 'center top-=100',
  //   end: 'bottom bottom',
  //   endTrigger: '.pageWrapper',
  //   onUpdate: (self) => {
  //     self.direction === -1 ? showCtaAnim.play() : showCtaAnim.reverse()
  //   },
  // })
}

// $(document).ready(() => {
//   $('.submenuItemLink').on('click', () => {
//     $('.mainNavBlock').removeClass('backdrop-blur-xl')
//     $('.menu').removeClass('text-bgColor')
//     $('.submenu-wrapper').removeClass('open')
//     $('.submenu-wrapper').css('display', 'none')
//     $('.tab-control').removeClass('open')
//     $('.tab-control').removeClass('underline')
//     $('#blur-overlay').css('display', 'none')
//     $('body').css('overflow', 'auto')
//   })
// })
