import { createHotelSwitcher } from '~resources/lib/hotel-switcher/hotel-switcher'

import '~resources/views/main'

// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
await import('jquery-ui/dist/jquery-ui')
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
await import('jquery-ui/ui/widgets/sortable')

$(() => {
  const $el = $('#hotel-rooms')

  $el.find('div.room').each(function () {
    const $card = $(this)
    $card.append('<div class="sort-handle"><i class="icon">drag_pan</i></div>')
  })

  $el.sortable({
    handle: 'div.sort-handle',
    items: '>div.room',
    placeholder: 'card',
    // containment: el,
    update() {
      const indexes: number[] = []
      $el.find('div.room').each(function () {
        indexes[indexes.length] = +$(this).data('id')
      })
      $.ajax({
        url: `${location.pathname}/position`,
        data: { indexes },
        method: 'put',
      })
    },
  })
  createHotelSwitcher(document.getElementsByClassName('content-header')[0])
})
