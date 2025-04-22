export default function (component) {
  const ship = component.querySelector('.ship');
  const level = component.querySelector('.level');
  const bar = component.querySelector('.bar');

  if (!ship || !level || !bar) return;

  let updateProgressHasRun = false;

  function updateProgress() {
    if (updateProgressHasRun) {
      ship.style.transition = 'unset';
      level.style.transition = 'unset';
    }

    const isMobile = window.matchMedia('(max-width: 1024px)').matches;
    const goal = parseFloat(component.dataset.goal) || 0;
    const levelValue = parseFloat(component.dataset.level) || 0;

    const shipWidth = ship.getBoundingClientRect().width;
    const barWidth = bar.getBoundingClientRect().width;
    const levelWidth = level.getBoundingClientRect().width;

    let progressShip, progressLevel;

    if (isMobile) {
      progressShip = 24 + (levelValue / goal) * (barWidth - shipWidth);
      progressLevel = progressShip + (shipWidth - levelWidth) / 2;
    } else {
      progressShip = (levelValue / goal) * (barWidth - shipWidth);
      progressLevel = progressShip + (shipWidth - levelWidth) / 2;
    }

    ship.style.left = `${progressShip}px`;
    level.style.left = `${progressLevel}px`;

    if (!updateProgressHasRun) {
      updateProgressHasRun = true;
    }
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.intersectionRatio >= 0.5) {
          updateProgress();
        }
      });
    },
    { threshold: 0.5 }
  );

  observer.observe(component);
  window.addEventListener('resize', updateProgress);
}
