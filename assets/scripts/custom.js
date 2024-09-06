import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

// Fade-in Animation
gsap.registerPlugin(ScrollTrigger)

// responsive
// const mm = gsap.matchMedia()

ScrollTrigger.refresh()
document.addEventListener('resize', () => {
  ScrollTrigger.refresh()
})

// mm.add('(min-width: 1180px)', () => {
//   gsap.set('.fade-in', { opacity: 0 })
//   gsap.set('.move-up', { y: 200 })
// })

ScrollTrigger.batch('.anim-fade-in', {
  onEnter: (elements) =>
    gsap.to(elements, { opacity: 1, stagger: 0.1, duration: 0.5 }),
  start: '8rem bottom',
  end: 'top top',
})

ScrollTrigger.batch('.anim-move-up', {
  onEnter: (elements) =>
    gsap.to(elements, { y: 0, opacity: 1, stagger: 0.1, duration: 0.3 }),
  start: '8rem bottom',
  end: 'top top',
})

ScrollTrigger.batch('.anim-move-down', {
  onEnter: (elements) =>
    gsap.to(elements, { y: 0, opacity: 1, stagger: 0.1, duration: 0.3 }),
  start: '8rem bottom',
  end: 'top top',
})

ScrollTrigger.batch('.anim-move-left', {
  onEnter: (elements) =>
    gsap.to(elements, {
      x: 0,
      opacity: 1,
      stagger: 0.2,
      duration: 0.8,
      ease: 'power3.out',
    }),
  start: '8rem bottom',
  end: 'top top',
})

ScrollTrigger.batch('.anim-move-right', {
  onEnter: (elements) =>
    gsap.to(elements, {
      x: 0,
      opacity: 1,
      stagger: 0.2,
      duration: 0.8,
      ease: 'power3.out',
    }),
  start: '8rem bottom',
  end: 'top top',
})

// document.querySelectorAll('hr').forEach(item => {
//   let color = item.previousElementSibling.lastChild.style !== undefined
//     ? item.previousElementSibling.lastChild.style.color
//     : item.previousElementSibling.style.color
//   color = color.length ? color : 'black'
//   item.style.backgroundColor = color
// })
