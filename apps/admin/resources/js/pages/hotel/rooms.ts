import '../main'
import 'jquery-ui/ui/data'
import 'jquery-ui/ui/widget'
import 'jquery-ui/ui/widgets/mouse'
import 'jquery-ui/ui/widgets/sortable'

// TODO fix glitches at drag
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
})
