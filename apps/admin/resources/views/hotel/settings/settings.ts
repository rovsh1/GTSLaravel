import { createPinia } from 'pinia'

import { createHotelSwitcher } from '~resources/lib/hotel-switcher/hotel-switcher'
import CancellationConditions from '~resources/views/hotel/settings/CancellationConditions.vue'
import MarkupConditions from '~resources/views/hotel/settings/MarkupConditions.vue'
import ResidenceConditions from '~resources/views/hotel/settings/ResidenceConditions.vue'

import { createVueInstance } from '~lib/vue'

import '~resources/views/main'

const pinia = createPinia()

createHotelSwitcher(document.getElementsByClassName('content-header')[0])

createVueInstance({
  rootComponent: ResidenceConditions,
  rootContainer: '#residence-conditions',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: CancellationConditions,
  rootContainer: '#cancellation-conditions',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: MarkupConditions,
  rootContainer: '#markup-conditions',
  plugins: [pinia],
})

// createVueInstance({
//   rootComponent: RoomMarkups,
//   rootContainer: '#room-markups',
// })

$((): void => {
  $('#btn-rules-add').click(function (e: any) {
    e.preventDefault()
    window.WindowDialog({
      url: $(this).data('url'),
      title: 'Новое правило',
      buttons: ['submit', 'cancel'],
    })
  })
})
