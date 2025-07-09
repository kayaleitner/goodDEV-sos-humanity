import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

// register Plugin
gsap.registerPlugin(ScrollTrigger)

// add Eventlistener refresh Scrolltrigger on resize
document.addEventListener('resize', () => {
  ScrollTrigger.refresh()
})

gsap.set('.fade-in', { opacity: 0 })
gsap.set('.move-in', { y: 150 })

ScrollTrigger.batch('.fade-in', {
  onEnter: (elements) => gsap.to(elements, { opacity: 1, stagger: 0.15, duration: 1 }),
  start: '20px bottom',
  end: 'top top'
})
ScrollTrigger.batch('.move-in', {
  onEnter: (elements) => gsap.to(elements, { y: 0, stagger: 0.15, duration: 0.5 }),
  start: '20px bottom',
  end: 'top top'
})

const parallaxBoxes = gsap.utils.toArray('.parallax')
parallaxBoxes.forEach((box, i) => {
  // Set up your animation
  const anim = gsap.to(box, { y: '-20%', scaleX: 1.2, scaleY: 1.2, ease: 'none' })
  // Use callbacks to control the state of the animation
  ScrollTrigger.create({
    trigger: box,
    animation: anim,
    scrub: 0.1,
    start: '0 bottom',
    end: 'top top',
    ease: 'none',
    markers: false
  })
})

const parallaxTextTopBottom = gsap.utils.toArray('.parallax-text-top-bottom')
parallaxTextTopBottom.forEach((box, i) => {
  // Set up your animation
  const anim = gsap.to(box, { x: '-30vh', ease: 'none' })
  // Use callbacks to control the state of the animation
  ScrollTrigger.create({
    trigger: box,
    animation: anim,
    scrub: 0.1,
    start: 'center bottom',
    end: 'center top',
    ease: 'none',
    markers: false
  })
})



const parallaxHero = gsap.utils.toArray('.parallax-hero')
parallaxHero.forEach((box, i) => {
  // Set up your animation
  const anim = gsap.to(box, { y: '-20rem', scaleX: 1, scaleY: 1, ease: 'none' })
  // Use callbacks to control the state of the animation
  ScrollTrigger.create({
    trigger: box,
    animation: anim,
    scrub: 0.1,
    start: 'top top',
    end: 'bottom top',
    ease: 'none',
    markers: false
  })
})


// const parallaxHeroText = gsap.utils.toArray('.parallax-hero-text')
// parallaxHeroText.forEach((box, i) => {
//   // Set up your animation
//   const anim = gsap.to(box, { y: '-10vh', ease: 'none' })
//   // Use callbacks to control the state of the animation
//   ScrollTrigger.create({
//     trigger: box,
//     animation: anim,
//     scrub: 0.1,
//     start: 'top top',
//     end: 'bottom top',
//     ease: 'none',
//     markers: false
//   })
// })

const parallaxHeroText = gsap.utils.toArray('.parallax-hero-text')
parallaxHeroText.forEach((box, i) => {
  // Set up your animation
  const anim = gsap.to(box, { y: '20rem', ease: 'none' })
  // Use callbacks to control the state of the animation
  ScrollTrigger.create({
    trigger: box,
    animation: anim,
    scrub: 0.1,
    start: 'top top',
    end: 'bottom top',
    ease: 'none',
    markers: false
  })
})


