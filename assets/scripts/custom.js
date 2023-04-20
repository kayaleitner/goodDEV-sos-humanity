import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

// Fade-in Animation
gsap.registerPlugin(ScrollTrigger)

// responsive
const mm = gsap.matchMedia()

document.addEventListener('resize', () => {
  ScrollTrigger.refresh()
})

mm.add('(min-width: 1280px)', () => {
  gsap.set('.fade-in',
    { opacity: 0 }
  )
  gsap.set('.move-up',
    { y: 200 }
  )
})

ScrollTrigger.batch('.fade-in', {
  onEnter: (elements) => gsap.to(elements, { opacity: 1, stagger: 0.1, duration: .5 }),
  start: '100px bottom',
  end: 'top top'
})

ScrollTrigger.batch('.move-up', {
  onEnter: (elements) => gsap.to(elements, { y: 0, stagger: 0.1, duration: .3 }),
  start: '100px bottom',
  end: 'top top'
})
