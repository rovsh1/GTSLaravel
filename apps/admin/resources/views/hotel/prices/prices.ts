import { createHotelSwitcher } from '~resources/lib/hotel-switcher/hotel-switcher'
import { createVueInstance } from '~resources/vue/vue'

import HotelPrices from './HotelPrices.vue'

import '~resources/views/main'

createHotelSwitcher(document.getElementsByClassName('content-header')[0])

createVueInstance({
  rootComponent: HotelPrices,
  rootContainer: '#hotel-prices',
})
