import { createHotelSwitcher } from '~resources/lib/hotel-switcher/hotel-switcher'

import { createVueInstance } from '~lib/vue'

import HotelPrices from './HotelPrices.vue'

import '~resources/views/main'

createHotelSwitcher()

createVueInstance({
  rootComponent: HotelPrices,
  rootContainer: '#hotel-prices',
})
