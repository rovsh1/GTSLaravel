import { createVueInstance } from '~resources/vue/vue'

import { createHotelSwitcher } from '~widgets/hotel-switcher/hotel-switcher'

import HotelPrices from './HotelPrices.vue'

import '~resources/views/main'

createHotelSwitcher(document.getElementsByClassName('content-header')[0])

createVueInstance({
  rootComponent: HotelPrices,
  rootContainer: '#hotel-prices',
})
