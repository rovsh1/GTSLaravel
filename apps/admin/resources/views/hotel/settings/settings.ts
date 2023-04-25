import { createVueInstance } from '~resources/lib/vue'

import HotelSettings from './HotelSettings.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: HotelSettings,
  rootContainer: '#hotel-settings',
})
