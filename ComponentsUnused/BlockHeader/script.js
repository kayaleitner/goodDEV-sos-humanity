import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import $ from 'jquery'

gsap.registerPlugin(ScrollTrigger)

export default (header) => {
  // const refs = buildRefs(carouselCities)
  // const data = getJSON(carouselCities)

  $('.wind-visual', header).each((index, elem) => {
    // Animate the wind visual using css varibales since it is a pseudo element
    // that cannot be targeted easily. Set up an object for animation parameters
    // so that animations can vary depending on the layout.
    let animationParams = {
      threshold: '50%',
    }

    if ($(header).hasClass('layout-1') || $(header).hasClass('layout-2')) {
      // Swoosh entries moving to the right, parallaxes to the left
      animationParams = {
        entryAnimation: {
          before: {
            translateX: {
              from: '-20px',
              to: '0',
            },
          },
          after: {
            translateX: {
              from: '-20px',
              to: '0',
            },
          },
        },

        parallaxAnimation: {
          before: {
            translateX: {
              to: '-100px',
            },
          },
          after: {
            translateX: {
              to: '-50px',
            },
          },
        },
      }
    } else if (
      $(header).hasClass('layout-3') ||
      $(header).hasClass('layout-4')
    ) {
      // Swoosh entries moving to the left, parallaxes to the right
      animationParams = {
        entryAnimation: {
          before: {
            translateX: {
              from: '20px',
              to: '0',
            },
          },
          after: {
            translateX: {
              from: '20px',
              to: '0',
            },
          },
        },

        parallaxAnimation: {
          before: {
            translateX: {
              to: '100px',
            },
          },
          after: {
            translateX: {
              to: '50px',
            },
          },
        },
      }
    }

    if ($(header).hasClass('layout-1')) {
      animationParams.threshold = '35%'
    } else if ($(header).hasClass('layout-2')) {
      animationParams.threshold = '15%'
    } else if ($(header).hasClass('layout-3')) {
      animationParams.threshold = '27%'
    } else if ($(header).hasClass('layout-4')) {
      animationParams.threshold = '30%'
    } else {
      // no styled layout detected, abort
      return
    }

    // Entry animation
    ScrollTrigger.create({
      trigger: elem,
      once: true,
      onEnter() {
        gsap.fromTo(
          elem,
          {
            '--visualOpacity': 0,
            '--beforeTranslateX':
              animationParams.entryAnimation.before.translateX.from,
            '--afterTranslateX':
              animationParams.entryAnimation.after.translateX.from,
          },
          {
            '--visualOpacity': 1,
            '--beforeTranslateX':
              animationParams.entryAnimation.before.translateX.to,
            '--afterTranslateX':
              animationParams.entryAnimation.after.translateX.to,
            duration: 0.5,
            onComplete() {
              // Parallax
              gsap.to(elem, {
                '--beforeTranslateX':
                  animationParams.parallaxAnimation.before.translateX.to,
                '--afterTranslateX':
                  animationParams.parallaxAnimation.after.translateX.to,
                ease: 'power1.out',
                scrollTrigger: {
                  trigger: elem,
                  start: `top ${animationParams.threshold}`,
                  end: 'bottom top',
                  scrub: true,
                  overwrite: true,
                },
              })
            },
          }
        )
      },
    })
  })
}
