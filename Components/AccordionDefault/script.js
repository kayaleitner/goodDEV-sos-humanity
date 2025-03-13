import $ from 'jquery'

export default function (component) {
  initComponent(component)
}

function initComponent (component) {
  component.querySelectorAll('[aria-controls]').forEach($panel => {
    $panel.addEventListener('click', togglePanel)
  })
  component.querySelectorAll('.panel-trigger').forEach($panel => {
    $panel.addEventListener('click', toggleClass)
  })

}

function togglePanel (e) {
  const $panel = $(e.currentTarget)

  if ($panel.attr('aria-expanded') === 'true') {
    $panel.attr('aria-expanded', 'false')
    $panel.next().attr('aria-hidden', 'true').slideUp()
  } else {
    $panel.attr('aria-expanded', 'true')
    $panel.next().attr('aria-hidden', 'false').slideDown()
  }
}

function toggleClass (e) {

  console.log(e)
  const $panel = $(e.currentTarget)
  const $panelParent = $panel.closest('.panel')

  if ($panelParent.hasClass('opened')) {
    $panelParent.removeClass('opened')
  } else {
    $panelParent.addClass('opened')
  }
}