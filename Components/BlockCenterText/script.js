import Rellax from 'rellax'

// Parallax Animation
const rellax = new Rellax('[data-parallax]', {
  //   speed: -2,
  center: true,
})

// eslint-disable-next-line no-undef
if (window && window.matchMedia('(max-width: 1280px)').matches) {
  //   rellax.destroy()
}
