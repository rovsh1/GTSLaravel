import { createVueInstance } from '~resources/vue/vue'

import HotelQuotas from './HotelQuotas.vue'

import './lib/main'

createVueInstance({
  rootComponent: HotelQuotas,
  rootContainer: '#hotel-quotas',
})
