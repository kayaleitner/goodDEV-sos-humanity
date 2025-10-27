/**
 * Donation Barometer Animation
 * - Smooth number animation via JS
 * - Fill and ship animations handled via CSS transitions
 */
export default function (component) {
  const ship = component.querySelector('.donation-barometer__ship');
  const fill = component.querySelector('.donation-barometer__fill');
  const displayType = component.dataset.displayType || 'count';
  // Support animating multiple variables independently if present in the markup
  const currentAmountEl = component.querySelector('.donation-barometer__current-amount');
  const donorCountEl = component.querySelector('.donation-barometer__donor-count');
  const barContainer = component.querySelector('.donation-barometer__bar-container');

  if (!ship || !fill || !barContainer) return;

  let hasAnimated = false;

  /**
   * Animate number increase (JS-driven)
   */
  function animateNumber(el, start, end, duration = 1200) {
    if (!el) return;
    let startTimestamp = null;

    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      const value = Math.floor(progress * (end - start) + start);
      el.textContent = value.toLocaleString('de-DE');
      if (progress < 1) requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
  }

  /**
   * Update positions (CSS transition handles smoothness)
   */
  function updateProgress(animated = true) {
    const goal = parseFloat(component.dataset.goal) || 0;
    const current = parseFloat(component.dataset.current) || 0;
    const donors = parseInt(component.dataset.donors, 10) || 0;
    const progressValue = displayType === 'count' ? donors : current;
    const progressPercent = goal > 0 ? Math.min(progressValue / goal, 1) : 0;

    const barWidth = barContainer.getBoundingClientRect().width;
    const shipWidth = ship.getBoundingClientRect().width;
    const shipPos = progressPercent * (barWidth - shipWidth);

    // CSS transitions handle the movement
    if (animated) {
      fill.style.transform = `scaleX(${progressPercent})`;
      ship.style.transform = `translateX(${shipPos}px)`;
    } else {
      // Disable transitions temporarily for instant layout updates
      fill.style.transition = 'none';
      ship.style.transition = 'none';
      fill.style.transform = `scaleX(${progressPercent})`;
      ship.style.transform = `translateX(${shipPos}px)`;
      // force reflow
      // eslint-disable-next-line no-unused-expressions
      fill.offsetWidth;
      fill.style.transition = '';
      ship.style.transition = '';
    }

    // Animate numbers once (support multiple variables)
    if (!hasAnimated && animated) {
      if (donorCountEl) {
        animateNumber(donorCountEl, 0, donors);
      }
      if (currentAmountEl) {
        animateNumber(currentAmountEl, 0, current);
      }
      hasAnimated = true;
    }
  }

  /**
   * Lazy trigger when visible
   */
  function startObserver() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !hasAnimated) {
            requestAnimationFrame(() => updateProgress(true));
            observer.unobserve(component);
          }
        });
      },
      { threshold: 0.8 }
    );
    observer.observe(component);
  }

  if ('requestIdleCallback' in window) {
    requestIdleCallback(startObserver);
  } else {
    window.addEventListener('load', startObserver);
  }

  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => updateProgress(false), 100);
  });
}
