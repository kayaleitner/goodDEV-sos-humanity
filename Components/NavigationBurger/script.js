import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import delegate from 'delegate-event-listener'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import { buildRefs } from '@/assets/scripts/helpers.js'

export default function (el) {
  let isMenuOpen
  const refs = buildRefs(el)
  const navigationHeight =
    parseInt(
      window.getComputedStyle(el).getPropertyValue('--navigation-height')
    ) || 0

  const isDesktopMediaQuery = window.matchMedia('(min-width: 1024px)')
  isDesktopMediaQuery.addEventListener('change', onBreakpointChange)

  el.addEventListener(
    'click',
    delegate('[data-ref="menuButton"]', onMenuButtonClick)
  )

  onBreakpointChange()

  function onMenuButtonClick (e) {
    isMenuOpen = !isMenuOpen
    refs.menuButton.setAttribute('aria-expanded', isMenuOpen)

    if (isMenuOpen) {
      el.setAttribute('data-status', 'menuIsOpen')
      el.classList.add('burgerMenuOpen')
      disableBodyScroll(refs.menu)
    } else {
      el.removeAttribute('data-status')
      el.classList.remove('burgerMenuOpen')
      enableBodyScroll(refs.menu)
    }
  }

  function onBreakpointChange () {
    if (!isDesktopMediaQuery.matches) {
      setScrollPaddingTop()
    }
  }

  function setScrollPaddingTop () {
    const scrollPaddingTop = document.getElementById('wpadminbar')
      ? navigationHeight + document.getElementById('wpadminbar').offsetHeight
      : navigationHeight
    document.documentElement.style.scrollPaddingTop = `${scrollPaddingTop}px`
  }

  console.log('NavigationBurger', el)

  const sections = gsap.utils.toArray([
    '#mainContent flynt-component',
    '#mainContent article'
  ])
  sections.forEach((section) => {
    gsap.to(section, {
      scrollTrigger: {
        trigger: section,
        start: 'top-=135 top',
        end: 'bottom top+=70',
        onToggle: (self) => {
          if (self.isActive) {
            console.log(
              'blur',
              self.trigger.dataset?.navstyle?.includes('blur'),
              el.classList
            )

            el.querySelectorAll('.logo').forEach((logo) =>
              logo.classList.remove('flex', 'hidden')
            )
            el.classList.remove(
              'bg-bgColor/50',
              'backdrop-blur-lg',
              'text-bltextColorack',
              'text-bgColor',
              'hamburger-grey'
            )

            self.trigger.dataset?.navstyle?.includes('blur') &&
              el.classList.add('backdrop-blur-lg')

            self.trigger.dataset?.navstyle?.includes('dark')
              ? el.querySelector('.logo_dark').classList.add('hidden')
              : el.querySelector('.logo_dark').classList.add('flex')
            !self.trigger.dataset?.navstyle?.includes('dark')
              ? el.querySelector('.logo_light').classList.add('hidden')
              : el.querySelector('.logo_light').classList.add('flex')

            self.trigger.dataset?.navstyle?.includes('dark')
              ? el.classList.add('text-bgColor')
              : el.classList.add('bg-bgColor/50', 'text-textColor', 'hamburger-grey')
          }
        }
      }
    })
  })

  // hide/show navigation on scroll
  const showAnim = gsap
    .from('[name="NavigationBurger"]', {
      yPercent: -150,
      paused: true,
      duration: 0.4
      // scrub: 0.5,
    })
    .progress(1)
  ScrollTrigger.create({
    start: 'center top-=100',
    end: 99999,
    onUpdate: (self) => {
      const menuWrapper = document.querySelector('[name="NavigationBurger"]')
      if (
        self.direction === -1 &&
        !menuWrapper.classList.contains('burgerMenuOpen')
      ) {
        showAnim.play()
      } else if (
        self.direction === 1 &&
        !menuWrapper.classList.contains('burgerMenuOpen')
      ) {
        showAnim.reverse()
      }
    }
  })
  // hide/show cta on scroll
  const showCtaAnim = gsap
    .from('#ctaMain', {
      yPercent: 300,
      paused: true,
      duration: 0.4
      // scrub: 0.5,
    })
    .progress(1)
  ScrollTrigger.create({
    start: 'center top-=100',
    end: 99999,
    onUpdate: (self) => {
      self.direction === -1 ? showCtaAnim.play() : showCtaAnim.reverse()
    }
  })
}
