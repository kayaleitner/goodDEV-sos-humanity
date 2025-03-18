import Rellax from 'rellax';

let rellax;
let initialized = false;

export default function (component) {
  // Rellax integration is currently broken and gives error messages,
  // so it's disabled until it's fixed again
  // @todo fix rellax
  // @marbleton

  // initParallax();

  console.log('asdf')
}

function initParallax() {
  if (!initialized) {
    initialized = true;
    setParallax();
    window.addEventListener('resize', setParallax);
  }
}

function setParallax() {
  if (isMobile()) {
    if (rellax) {
      rellax.destroy();
      rellax = null;
    }
  } else {
    if (!rellax) {
      rellax = new Rellax('[data-parallax]', {
        speed: 2,
        center: true,
        percentage: 0.5
      });
    } else {
      rellax.refresh();
    }
  }
}

function isMobile() {
  return window.matchMedia('(max-width: 1024px)').matches;
}
