import $ from 'jquery'

$(document).ready(function () {
  const headerHeight = $('header').outerHeight() // Target your header navigation here
  $('a').click(function (e) {
    let targetHref = $(this).attr('href')

    targetHref = targetHref.substring(targetHref.indexOf('#'))

    $('html, body').animate({
      scrollTop: $(targetHref).offset().top - headerHeight // Add it to the calculation here
    }, 500)

    e.preventDefault()
  })
})
