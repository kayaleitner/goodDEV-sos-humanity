import '@lottiefiles/lottie-player'

export default function (component) {
  const players = component.querySelectorAll('lottie-player')

  Array.from(players).forEach(player => {
    player.setAttribute('autoplay', true)
    player.play()

    // Wait for Lottie to be ready
    player.addEventListener('load', () => {
      const originalAnimationData = player.getLottie()

      const swapColors = (reverse = false) => {
        // Clone animation
        const newData = JSON.parse(JSON.stringify(originalAnimationData))

        const yellow = [0.9255, 0.9333, 0.1451]
        const blue = [0.2078, 0.3608, 0.7882]

        const findAndReplaceColor = (data, from, to) => {
          data.layers.forEach(layer => {
            if (layer.shapes) {
              layer.shapes.forEach(shape => {
                if (shape.ty === 'fl' && shape.c && shape.c.k) {
                  const color = shape.c.k
                  const isMatch = color.every((c, i) => Math.abs(c - from[i]) < 0.01)
                  if (isMatch) shape.c.k = to
                }
              })
            }
          })
        }

        if (reverse) {
          findAndReplaceColor(newData, yellow, blue)
          findAndReplaceColor(newData, blue, yellow)
        } else {
          // Reset
          newData.layers = JSON.parse(JSON.stringify(originalAnimationData.layers))
        }

        player.load(newData)
      }

      const wrapper = player.closest('.lottieWrapper')
      if (wrapper) {
        wrapper.addEventListener('mouseenter', () => swapColors(true))
        wrapper.addEventListener('mouseleave', () => swapColors(false))
      }
    })
  })
}
