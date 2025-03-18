export default function (component) {

  console.log('GridPostsLatest', component);

  const list = component.querySelector('ul.grid');
  const loadMoreButton = component.querySelector('[data-action="loadMore"]');
  const filterButtons = component.querySelectorAll('[data-action="loadFilter"]');

  let filters = {
    years: '',
    childTerms: '',
    time: '',
  };

  if (!list || !loadMoreButton) return;

  loadMoreButton.addEventListener('click', (e) => onLoadMore(e, list, loadMoreButton, filters));
  filterButtons.forEach(button => {
    button.addEventListener('click', (e) => onLoadFilter(e, list, loadMoreButton, filters));
  });
}

async function onLoadMore(e, list, loadMoreButton, filters) {
  e.preventDefault();

  try {
    const response = await fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        action: 'get_posts',
        count: 4,
        offset: list.querySelectorAll('li').length,
        category: list.dataset.category,
        filters: JSON.stringify(filters),
      }),
    });

    const res = await response.text();
    list.insertAdjacentHTML('beforeend', res);

    if ((res.match(/grid-item/g) || []).length < 4) {
      loadMoreButton.style.display = 'none';
    }
  } catch (err) {
    console.error(err);
  }
}

async function onLoadFilter(e, list, loadMoreButton, filters) {
  e.preventDefault();

  list.innerHTML = '';
  loadMoreButton.style.display = 'block';

  const target = e.currentTarget;
  const filterType = target.closest('[data-type]')?.dataset.type;

  if (filterType) {
    filters[filterType] = target.dataset.term;
  }

  document.querySelectorAll('[data-action="loadFilter"]').forEach(btn => btn.classList.remove('active'));
  target.classList.add('active');

  try {
    const response = await fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        action: 'get_posts',
        count: 7,
        offset: 0,
        category: list.dataset.category,
        filters: JSON.stringify(filters),
      }),
    });

    const res = await response.text();
    list.insertAdjacentHTML('beforeend', res);

    if ((res.match(/grid-item/g) || []).length < 7) {
      loadMoreButton.style.display = 'none';
    }
  } catch (err) {
    console.error(err);
  }
}
