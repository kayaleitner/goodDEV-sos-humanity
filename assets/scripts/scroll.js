import $ from 'jquery'

$(document).ready(function () {
  var headerHeight = $('header').outerHeight() // Target your header navigation here
  $('.menu-submenu li a').click(function (e) {
    var targetHref = $(this).attr('href')

    targetHref = targetHref.substring(targetHref.indexOf('#'))

    $('html, body').animate({
      scrollTop: $(targetHref).offset().top - headerHeight // Add it to the calculation here
    }, 500)

    e.preventDefault()
  })
})

$(document).ready(function () {
  const vh = window.innerHeight * 0.01
  document.documentElement.style.setProperty('--vh', `${vh}px`)
})

$(document).ready(function () {
  $('.panel').each(function () {
    var $accordionSection = $(window.location.hash)
    var $hash = window.location.hash.substring(1)
    var $show = $(this).attr('id')

    if ($hash === $show) {
      $accordionSection.addClass('opened')
      $('.panel-trigger', $accordionSection).attr('aria-expanded', 'true')
      $('.panel-content', $accordionSection).attr('aria-hidden', 'false')
      $('.panel-content', $accordionSection).css('display', 'block')
    }
  })
})
