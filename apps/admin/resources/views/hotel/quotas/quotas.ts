import { createVueInstance } from '~resources/vue/vue'

import HotelQuotas from './HotelQuotas.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: HotelQuotas,
  rootContainer: '#hotel-quotas',
})
