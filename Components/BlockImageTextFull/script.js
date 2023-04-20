import Rellax from 'rellax'

// Parallax Animation
const rellax = new Rellax('[data-parallax]', {
  speed: -2,
  center: true
})

if (window.matchMedia('(max-width: 1280px)').matches) {
  rellax.destroy()
}
