import '../main'
import '../../app/plugins/controls/coordinates-input'

import 'jquery-ui/ui/widgets/sortable'

$(document).ready(() => {
  $(document).ready(() => {
    (function (el) {
      el.find('div.room').each(function () {
        const $card = $(this)
        $card.append('<div class="sort-handle"><i class="icon">drag_pan</i></div>')
      })

      el.sortable({
        handle: 'div.sort-handle',
        items: '>div.room',
        placeholder: 'card',
        // containment: el,
        update() {
          const indexes: number[] = []
          el.find('div.room').each(function () {
            indexes[indexes.length] = +$(this).data('id')
          })
          $.ajax({
            url: `${location.pathname}/position`,
            data: { indexes },
            method: 'put',
          })
        },
      })
    }($('#hotel-rooms')))
  })
})
