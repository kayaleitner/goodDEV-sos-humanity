import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

export default function (el) {
  const navigationHeight = parseInt(window.getComputedStyle(el).getPropertyValue('--navigation-height')) || 0

  const isDesktopMediaQuery = window.matchMedia('(min-width: 1024px)')
  isDesktopMediaQuery.addEventListener('change', onBreakpointChange)

  onBreakpointChange()

  function onBreakpointChange () {
    if (isDesktopMediaQuery.matches) {
      setScrollPaddingTop()
    }
  }

  function setScrollPaddingTop () {
    const scrollPaddingTop = document.getElementById('wpadminbar')
      ? navigationHeight + document.getElementById('wpadminbar').offsetHeight
      : navigationHeight
    document.documentElement.style.scrollPaddingTop = `${scrollPaddingTop}px`
  }

  const sections = gsap.utils.toArray(['#mainContent flynt-component', '.single-post #mainContent article', '.single-people #mainContent article'])
  sections.forEach((section) => {
    gsap.to(section, {
      scrollTrigger: {
        trigger: section,
        start: 'top-=135 top',
        end: 'bottom top+=70',
        onToggle: (self) => {
          if (self.isActive) {
            const button = el.querySelector('#ctaMenu')

            el.querySelectorAll('.logo').forEach((logo) => logo.classList.remove('flex', 'hidden'))
            el.classList.remove('bg-white/50', 'backdrop-blur-md', 'text-grey', 'text-white')
            button.classList.remove(
              '[&_a]:bg-green', '[&_a]:hover:bg-cbegreen', '[&_a]:hover:text-white',
              '[&_a]:bg-white', '[&_a]:hover:bg-green'
            )

            self.trigger.dataset?.navstyle?.includes('blur') && el.classList.add('backdrop-blur-md')

            self.trigger.dataset?.navstyle?.includes('dark') ? el.querySelector('.logo_dark').classList.add('hidden') : el.querySelector('.logo_dark').classList.add('flex')
            !self.trigger.dataset?.navstyle?.includes('dark') ? el.querySelector('.logo_light').classList.add('hidden') : el.querySelector('.logo_light').classList.add('flex')

            self.trigger.dataset?.navstyle?.includes('dark') ? el.classList.add('text-white') : el.classList.add('bg-white/50', 'text-grey')
            self.trigger.dataset?.navstyle?.includes('dark') ? button.classList.add('[&_a]:bg-white', '[&_a]:hover:bg-green') : button.classList.add('[&_a]:bg-green', '[&_a]:hover:bg-cbegreen', '[&_a]:hover:text-white')
          }
        }
      }
    })
  })

  // hide/show navigation on scroll
  const showAnim = gsap.from('[name="NavigationMain"]', {
    yPercent: -100,
    paused: true,
    duration: 0.4
    // scrub: 0.5,
  }).progress(1)
  ScrollTrigger.create({
    start: 'center top-=100',
    end: 99999,
    onUpdate: (self) => {
      self.direction === -1 ? showAnim.play() : showAnim.reverse()
    }
  })
  // hide/show cta on scroll
  const showCtaAnim = gsap.from('#ctaMain', {
    yPercent: 300,
    paused: true,
    duration: 0.4
    // scrub: 0.5,
  }).progress(1)
  ScrollTrigger.create({
    start: 'center top-=100',
    end: 99999,
    onUpdate: (self) => {
      self.direction === -1 ? showCtaAnim.play() : showCtaAnim.reverse()
    }
  })
}
