import CancellationConditions from '~resources/views/hotel/settings/CancellationConditions.vue'
import PriceConditions from '~resources/views/hotel/settings/PriceConditions.vue'
import ResidenceConditions from '~resources/views/hotel/settings/ResidenceConditions.vue'

import { createVueInstance } from '~lib/vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: ResidenceConditions,
  rootContainer: '#living-conditions',
})

createVueInstance({
  rootComponent: CancellationConditions,
  rootContainer: '#cancellation-conditions',
})

createVueInstance({
  rootComponent: PriceConditions,
  rootContainer: '#price-conditions',
})

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
