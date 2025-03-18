import 'core-js/es/number';
import Swiper, { Navigation, A11y, Autoplay } from 'swiper/swiper.esm';
import 'swiper/swiper-bundle.css';

Swiper.use([Navigation, A11y, Autoplay]);

export default function (component) {
  const props = getInitialProps(component);
  const slider = component.querySelector('[data-slider]');
  const buttonNext = component.querySelector('[data-slider-button="next"]');
  const buttonPrev = component.querySelector('[data-slider-button="prev"]');

  if (!slider) return;

  initSlider(slider, buttonNext, buttonPrev, props);
}

function getInitialProps(component) {
  let data = {};
  try {
    const script = component.querySelector('script[type="application/json"]');
    if (script) {
      data = JSON.parse(script.textContent);
    }
  } catch (e) {
    console.error('Error parsing JSON data:', e);
  }
  return data;
}

function initSlider(slider, buttonNext, buttonPrev, props) {
  const { options } = props;
  if (!options) return;

  const config = {
    navigation: {
      nextEl: buttonNext,
      prevEl: buttonPrev
    },
    a11y: options.a11y || false
  };

  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    };
  }

  new Swiper(slider, config);
}
