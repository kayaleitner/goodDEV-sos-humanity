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

  const sections = gsap.utils.toArray('#mainContent flynt-component')
  sections.forEach((section) => {
    gsap.to(section, {
      scrollTrigger: {
        trigger: section,
        // start: 'top top+=100',
        start: "top top",
        end: "bottom top+=70",
        onToggle: (self) => {
          if (self.isActive) {
            el.classList.remove('bg-white/40', 'backdrop-blur-md', 'text-grey', 'text-white')
            el.querySelectorAll('.logo').forEach((logo) => logo.classList.remove('flex', 'hidden'))
            self.trigger.dataset?.navstyle?.includes('dark') ? el.querySelector('.logo_dark').classList.add('hidden') : el.querySelector('.logo_dark').classList.add('flex');
            !self.trigger.dataset?.navstyle?.includes('dark') ? el.querySelector('.logo_light').classList.add('hidden') : el.querySelector('.logo_light').classList.add('flex');
            self.trigger.dataset?.navstyle?.includes('dark') ? el.classList.add('text-white') : el.classList.add('bg-white/40', 'text-grey');
            self.trigger.dataset?.navstyle?.includes('blur') && el.classList.add('backdrop-blur-md');
          }
        }
      }
    })
  })

  // hide/show navigation on scroll
  const showAnim = gsap.from('[name="NavigationMain"]', {
    yPercent: -100,
    paused: true,
    duration: 0.4,
    // scrub: 0.5,
    markers: true,
  }).progress(1)
  ScrollTrigger.create({
    start: 'center top-=100',
    end: 99999,
    onUpdate: (self) => {
      self.direction === -1 ? showAnim.play() : showAnim.reverse()
    }
  })
}
