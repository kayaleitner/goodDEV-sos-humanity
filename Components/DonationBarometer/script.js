export default function (component) {
  const ship = component.querySelector('.donation-barometer__ship');
  const fill = component.querySelector('.donation-barometer__fill');

  if (!ship || !fill) return;

  let hasAnimated = false;

  function updateProgress() {
    const goal = parseFloat(component.dataset.goal) || 0;
    const current = parseFloat(component.dataset.current) || 0;
    const progressPercent = goal > 0 ? Math.min(current / goal, 1) : 0;

    const barWidth = component.querySelector('.donation-barometer__bar-container').offsetWidth;
    const shipWidth = ship.offsetWidth;

    const shipPos = progressPercent * (barWidth - shipWidth);
    const fillWidth = progressPercent * 100;

    fill.style.width = `${fillWidth}%`;
    ship.style.left = `${shipPos}px`;
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
