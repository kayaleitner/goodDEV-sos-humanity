export default function (component) {
  const ship = component.querySelector('.donation-barometer__ship');
  const level = component.querySelector('.donation-barometer__level');
  const fill = component.querySelector('.donation-barometer__fill');

  if (!ship || !level || !fill) return;

  let hasAnimated = false;

  function updateProgress() {
    const goal = parseFloat(component.dataset.goal) || 0;
    const current = parseFloat(component.dataset.current) || 0;
    const progressPercent = goal > 0 ? Math.min(current / goal, 1) : 0;

    const barWidth = component.querySelector('.donation-barometer__bar-container').offsetWidth;
    const shipWidth = ship.offsetWidth;
    const levelWidth = level.offsetWidth;

    const shipPos = progressPercent * (barWidth - shipWidth);
    const levelPos = shipPos + (shipWidth - levelWidth) / 2;
    const fillWidth = progressPercent * 100;

    fill.style.width = `${fillWidth}%`;
    ship.style.left = `${shipPos}px`;
    level.style.left = `${levelPos}px`;
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
