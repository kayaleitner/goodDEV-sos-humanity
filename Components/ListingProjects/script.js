import $ from 'jquery'

$('#loadMore').on('click', function () {
  const $this = $(this)
  const $list = $('#projects')

  $this.text('Loading...')

  const offset = 4

  console.log('load more', $list)
  $.ajax({
    type: 'POST',
    url: '/wp-admin/admin-ajax.php',
    dataType: 'html',
    data: {
      action: 'get_posts',
      count: 4,
      offset,
    },
  }).then(
    (res) => {
      console.log('list', $list)
      $list.append(res)
      if ((res.match(/post/g) || []).length <= 4) {
        $this.hide()
      }
    },
    (err) => {
      console.log(err)
    }
  )
})
