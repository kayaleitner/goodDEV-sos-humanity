import $ from 'jquery'

$(document).ready(function () {
  var header = $('.mainHeader')
  var buttonSubmenu = $('.open-submenu')

  $(header).mouseenter(function () {
    $(header).addClass('mainHeader--open')
    $(header).removeClass('slideUp')
  })

  $(header).mouseleave(function () {
    
    var y = $(document).scrollTop()
    if (y > 0) {
      $(header).addClass('slideUp')
      $('nav').addClass('mainHeader--open')
    } else {
      $('nav').removeClass('mainHeader--open')
    }
    $(header).removeClass('mainHeader--open')
  })
})

$(document).scroll(function () {
  var y = $(this).scrollTop()
  var header = $('.mainHeader')

  if ($('.mainHeader:hover').length != 0) {
    $(header).removeClass('slideUp')
  } else if (y > 0) {
    $(header).addClass('slideUp')
    $('nav').addClass('mainHeader--open')
  } else {
    $('nav').removeClass('mainHeader--open')
    $(header).removeClass('slideUp')
  }
})


