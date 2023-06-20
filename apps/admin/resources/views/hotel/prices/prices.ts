import { createVueInstance } from '~lib/vue'

import HotelPrices from './HotelPrices.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: HotelPrices,
  rootContainer: '#hotel-prices',
})
