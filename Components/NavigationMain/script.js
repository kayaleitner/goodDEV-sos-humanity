// import gsap from 'gsap'
// import ScrollTrigger from 'gsap/ScrollTrigger'
import $ from 'jquery'
import { buildRefs } from '@/assets/scripts/helpers.js'

export default function (el) {
  const refs = buildRefs(el)
  initNavState()

  function initNavState() {
    // const prevScrollPos = $(window).scrollTop()
    const currentScrollPos = $(window).scrollTop()

    if (currentScrollPos > 0) {
      setScrolledState()
    } else {
      unsetScrolledState()
    }
  }

  function setScrolledState() {
    refs.nav.classList.remove('text-bgColor')
    refs.nav.classList.add('bg-bgColor')
    refs.logo.classList.add('hidden')
    refs.logoDark.classList.remove('hidden')
    $('.after-marker', refs.nav).addClass('after-marker--dark')
    $('.button--outlineWhite', refs.nav)
      .removeClass('button--outlineWhite')
      .addClass('button--accent')
  }

  function unsetScrolledState() {
    refs.nav.classList.remove('bg-bgColor')
    refs.nav.classList.add('text-bgColor')
    refs.logoDark.classList.add('hidden')
    refs.logo.classList.remove('hidden')
    $('.after-marker', refs.nav).removeClass('after-marker--dark')
    $('.button--accent', refs.nav)
      .removeClass('button--accent')
      .addClass('button--outlineWhite')
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

  // const sections = gsap.utils.toArray([
  //   '#mainContent flynt-component',
  //   '.single-post #mainContent article',
  //   '.single-people #mainContent article',
  // ])
  // sections.forEach((section) => {
  //   gsap.to(section, {
  //     scrollTrigger: {
  //       trigger: section,
  //       start: 'top-=135 top',
  //       end: 'bottom top+=70',
  //       onToggle: (self) => {
  //         if (self.isActive) {
  //           const button = el.querySelector('#ctaMenu')

  //           el.querySelectorAll('.logo').forEach((logo) =>
  //             logo.classList.remove('flex', 'hidden')
  //           )
  //           el.classList.remove(
  //             'bg-bgColor/50',
  //             'backdrop-blur-xl',
  //             'text-textColor',
  //             'text-bgColor'
  //           )
  //           button.classList.remove(
  //             '[&_a]:bg-accentColor',
  //             '[&_a]:hover:bg-brandColor',
  //             '[&_a]:hover:text-bgColor',
  //             '[&_a]:bg-bgColor',
  //             '[&_a]:hover:bg-accentColor'
  //           )

  //           self.trigger.dataset?.navstyle?.includes('blur') &&
  //             el.classList.add('backdrop-blur-xl')

  //           self.trigger.dataset?.navstyle?.includes('dark')
  //             ? el.querySelector('.logo_dark').classList.add('hidden')
  //             : el.querySelector('.logo_dark').classList.add('flex')
  //           !self.trigger.dataset?.navstyle?.includes('dark')
  //             ? el.querySelector('.logo_light').classList.add('hidden')
  //             : el.querySelector('.logo_light').classList.add('flex')

  //           self.trigger.dataset?.navstyle?.includes('dark')
  //             ? el.classList.add('text-bgColor')
  //             : el.classList.add('bg-bgColor/50', 'text-textColor')
  //           self.trigger.dataset?.navstyle?.includes('dark')
  //             ? button.classList.add('[&_a]:bg-bgColor', '[&_a]:hover:bg-accentColor')
  //             : button.classList.add(
  //               '[&_a]:bg-accentColor',
  //               '[&_a]:hover:bg-brandColor',
  //               '[&_a]:hover:text-bgColor'
  //             )
  //         }
  //       }
  //     }
  //   })
  // })

  // hide/show navigation on scroll
  // const showAnim = gsap
  //   .from('[name="NavigationMain"]', {
  //     yPercent: -100,
  //     paused: true,
  //     duration: 0.4,
  //     // scrub: 0.5,
  //   })
  //   .progress(1)

  // ScrollTrigger.create({
  //   start: 'center top-=100',
  //   endTrigger: '.pageWrapper',
  //   end: 'bottom bottom',
  //   onUpdate: (self) => {
  //     const submenuWrapper = document.querySelector('.submenu-wrapper')
  //     if (self.direction === -1 && !submenuWrapper.classList.contains('open')) {
  //       showAnim.play()
  //     } else if (
  //       self.direction === 1 &&
  //       !submenuWrapper.classList.contains('open')
  //     ) {
  //       showAnim.reverse()
  //     }
  //   },
  //   onRefresh: (self) => {
  //     // scroll to element id on load
  //     const url = window.location.href
  //     const hash = url.substring(url.indexOf('#') + 1)
  //     if (hash) {
  //       const element = document.getElementById(hash)
  //       if (element) {
  //         element.scrollIntoView()
  //       }
  //     }
  //   },
  // })
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
