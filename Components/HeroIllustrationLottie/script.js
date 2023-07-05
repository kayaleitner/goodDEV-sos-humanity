import '@lottiefiles/lottie-player'
import { create } from '@lottiefiles/lottie-interactivity'
import ScrollTrigger from 'gsap/ScrollTrigger'

export default function (heroIllustrationLottie) {
  const players = heroIllustrationLottie.querySelectorAll('lottie-player')
  Array.from(players).forEach((player) => {
    const animationType = player.dataset.animationtype
    // switch case for animationType
    switch (animationType) {
      case 'playOnce':
        player.setAttribute('autoplay', true)
        return

      case 'loop':
        // create({
        //     mode: 'scroll',
        //     player: player,
        //     actions: [
        //         {
        //             type: 'loop',
        //             visibility: [0, 1],
        //             frames: [0, 100],
        //         }
        //     ]
        // })
        player.setAttribute('autoplay', true)
        player.setAttribute('loop', true)
        return

      case 'scrubbed':
        fetch(player.src) // eslint-disable-line
          .then((response) => response.json())
          .then((data) => {
            // Get the number of frames in the animation
            const numFrames = data.op - data.ip
            create({
              mode: 'scroll',
              player,
              actions: [
                {
                  type: 'seek',
                  visibility: [0, 1],
                  frames: [0, numFrames]
                }
              ]
            })
            ScrollTrigger.refresh()
          })
        break

      default:
    }
  })

  ScrollTrigger.refresh()
}

// Autoplay on scroll --. not working
// create({
//     mode: 'scroll',
//     player: player,
//     actions: [
//         {
//             visibility: [0.50, 1.0],
//             type: 'play'
//         }
//     ]
// })

// Play on hover
// create({
//     mode: "cursor",
//     player: player,
//     actions: [
//         {
//             type: "hover",
//             forceFlag: false,
//         }
//     ]
// })
// toggle on click
// create({
//     mode: 'cursor',
//     player: player,
//     actions: [
//         {
//             type: 'toggle',
//         }
//     ]
// })
// play on click
// create({
//     mode: 'cursor',
//     player: player,
//     actions: [
//         {
//             type: 'click',
//         }
//     ]
// })
