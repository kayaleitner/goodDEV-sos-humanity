import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';

export default function (component) {
  const menu = component.querySelector('.menu');
  const menuButton = component.querySelector('.hamburger');
  const toggleMenuButtons = component.querySelectorAll('[data-toggle-menu]');
  const panelToggles = component.querySelectorAll('[aria-controls]');
  const submenuLinks = component.querySelectorAll('.open-submenu .menu-link');

  if (!menu || !menuButton) return;

  // Bind event listeners
  toggleMenuButtons.forEach((btn) => {
    btn.addEventListener('click', () => triggerMenu(component, menu, menuButton));
  });

  panelToggles.forEach((btn) => {
    btn.addEventListener('click', (e) => togglePanel(e));
  });

  submenuLinks.forEach((link) => {
    link.addEventListener('click', (e) => toggleClass(e));
  });
}

function triggerMenu(component, menu, menuButton) {
  component.classList.toggle('flyntComponent-menuIsOpen');
  const expanded = menuButton.getAttribute('aria-expanded') === 'true';
  menuButton.setAttribute('aria-expanded', expanded ? 'false' : 'true');

  if (component.classList.contains('flyntComponent-menuIsOpen')) {
    disableBodyScroll(menu);
  } else {
    enableBodyScroll(menu);
  }
}

function togglePanel(e) {
  const panel = e.currentTarget;
  const nextEl = panel.nextElementSibling;

  const isExpanded = panel.getAttribute('aria-expanded') === 'true';
  panel.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');

  if (nextEl) {
    nextEl.setAttribute('aria-hidden', isExpanded ? 'true' : 'false');
    nextEl.classList.toggle('flexMe');
  }
}

function toggleClass(e) {
  const link = e.currentTarget;
  const parent = link.closest('.open-submenu');

  if (parent) {
    parent.classList.toggle('opened');
  }
}
