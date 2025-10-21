export default function (component) {
  const ship = component.querySelector('.donation-barometer__ship');
  const fill = component.querySelector('.donation-barometer__fill');
  const amountEl = component.querySelector('.donation-barometer__current--amount');
  const displayType = component.dataset.displayType || 'count';

  if (!ship || !fill) return;

  let hasAnimated = false;

  function animateNumber(el, start, end, duration = 1500) {
    if (!el) return;
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      const value = Math.floor(progress * (end - start) + start);
      el.textContent = value.toLocaleString('de-DE'); // deutsches Format mit Punkt
      if (progress < 1) {
        window.requestAnimationFrame(step);
      }
    };
    window.requestAnimationFrame(step);
  }

  function updateProgress() {
    const goal = parseFloat(component.dataset.goal) || 0;
    const current = parseFloat(component.dataset.current) || 0;
    const donors = parseInt(component.dataset.donors, 10) || 0;
    const progressValue = displayType === 'count' ? donors : current;
    const progressPercent = goal > 0 ? Math.min(progressValue / goal, 1) : 0;

    const barWidth = component.querySelector('.donation-barometer__bar-container').offsetWidth;
    const shipWidth = ship.offsetWidth;

    const shipPos = progressPercent * (barWidth - shipWidth);
    const fillWidth = progressPercent * 100;

    fill.style.width = `${fillWidth}%`;
    ship.style.left = `${shipPos}px`;

    if (amountEl) {
      if (displayType === 'count') {
        animateNumber(amountEl, 0, donors);
      } else {
        animateNumber(amountEl, 0, current);
      }
    }
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.intersectionRatio >= 0.5 && !hasAnimated) {
          updateProgress();
          hasAnimated = true;
        }
      });
    },
    { threshold: 0.5 }
  );

  observer.observe(component);
  window.addEventListener('resize', updateProgress);
}
