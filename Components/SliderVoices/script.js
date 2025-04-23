import 'core-js/es/number';
import Swiper from 'swiper';
import { Navigation, A11y, Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/swiper-bundle.css';

Swiper.use([Navigation, A11y, Autoplay, Pagination]);

export default function (component) {
  const props = getInitialProps(component);
  const slider = component.querySelector('[data-slider]');
  const buttonNext = component.querySelector('[data-slider-button="next"]');
  const buttonPrev = component.querySelector('[data-slider-button="prev"]');
  const pagination = component.querySelector('[data-slider-pagination]');
  const slides = component.querySelectorAll('.swiper-slide');

  if (!slider || slides.length <= 1) return;

  initSlider(slider, buttonNext, buttonPrev, pagination, props);
}

function getInitialProps(component) {
  let data = {};
  try {
    const script = component.querySelector('script[type="application/json"]');
    if (script) {
      data = JSON.parse(script.textContent);
    }
  } catch (e) {
    console.error('Error parsing slider props JSON:', e);
  }
  return data;
}

function initSlider(slider, buttonNext, buttonPrev, pagination, props) {
  const { options } = props;
  if (!options) return;

  const config = {
    a11y: options.a11y,
    loop: true,
    slidesPerView: 1,
    navigation: {
      nextEl: buttonNext,
      prevEl: buttonPrev,
    },
    pagination: {
      el: pagination,
      clickable: true,
    },
  };

  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed,
    };
  }

  new Swiper(slider, config);
}
