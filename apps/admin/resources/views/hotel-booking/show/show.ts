import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import BookingRooms from './BookingRooms.vue'
import ControlPanel from './ControlPanel.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: BookingRooms,
  rootContainer: '#booking-rooms',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: ControlPanel,
  rootContainer: '#booking-control-panel',
  plugins: [pinia],
})
