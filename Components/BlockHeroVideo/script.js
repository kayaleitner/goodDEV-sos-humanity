import '@lottiefiles/lottie-player'

export default function (component) {
  const players = component.querySelectorAll('lottie-player')

  Array.from(players).forEach(player => {
    player.setAttribute('autoplay', true)
    player.play()

  })
}
