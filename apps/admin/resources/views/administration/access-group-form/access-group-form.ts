import Tab from '~resources/js/app/components/permissions-control/tab'

import '~resources/views/main'

$(document).ready(() => {
  const $tabs = $('#permissions-tabs button')
  const $menus = $('#permissions > div.permissions-category-menu')

  $tabs
    .click(function () {
      $tabs.filter('.active')
        .removeClass('active')
      $(this)
        .addClass('active')

      const category = $(this).data('category')

      $menus.each((_, m) => {
        if ($(m).data('category') === category) {
          $(m).show()
        } else {
          $(m).hide()
        }
      })
    })
    .each(function () {
      new Tab(
        $(this),
        $menus.filter(`[data-category="${$(this).data('category')}"]`),
      )
    })
})