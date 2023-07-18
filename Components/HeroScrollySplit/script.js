import 'swiper/css/bundle'
import gsap from 'gsap'
import { buildRefs } from '@/assets/scripts/helpers.js'

export default function (heroScrollySplit) {
  const refs = buildRefs(heroScrollySplit, true)

  const mm = gsap.matchMedia()
  const breakPoint = 780

  mm.add(
    {
      // set up any number of arbitrarily-named conditions. The function below will be called when ANY of them match.
      isDesktop: `(min-width: ${breakPoint}px) and (prefers-reduced-motion: no-preference)`,
      isMobile: `(max-width: ${
        breakPoint - 1
      }px) and (prefers-reduced-motion: no-preference)`,
    },
    (context) => {
      const { isDesktop, isMobile } = context.conditions

      const timeline = gsap.timeline({
        scrollTrigger: {
          trigger: refs.section,
          pin: true,
          start: 'top top',
          end: 'bottom+=50% bottom',
          scrub: true,
          // markers: true,
          snap: {
            snapTo: 1,
            duration: { min: 0.01, max: 0.2 },
            delay: 0,
            ease: 'power1.in',
            inertia: true,
            directional: true,
          },
        },
      })
      timeline
        .from(refs.text_2, {
          opacity: isDesktop && 1,
          duration: 0.2,
        })
        .to(
          refs.left,
          {
            y: isDesktop && '100vh',
            x: isMobile && '-100vw',
          },
          0
        )
        .to(
          refs.right,
          {
            y: isDesktop && '-100vh',
            x: isMobile && '100vw',
          },
          0
        )
        .to(
          refs.text_2,
          {
            opacity: isDesktop && 0,
            duration: 0.2,
          },
          0
        )
        .to(
          refs.text_3,
          {
            opacity: isDesktop && 1,
            duration: 0.2,
          },
          0
        )
        .to(
          refs.text_1,
          {
            opacity: isDesktop && 1,
            duration: 0.2,
          },
          0
        )
        .to(
          '.scrollyDot',
          {
            y: -40,
            width: (i) => (i <= 5 ? i * (i - 2) - 1 : (10 - i) * (i - 2) - 1),
          },
          0
        )
    }
  )

  // undhide h1s that were hidden because of content shift on page load
  refs.left[0].querySelector('h1').classList.remove('hidden')
  refs.left[2].querySelector('h1').classList.remove('hidden')
}
