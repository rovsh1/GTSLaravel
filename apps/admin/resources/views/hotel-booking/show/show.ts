import { createVueInstance } from '~lib/vue'

import BookingRooms from './BookingRooms.vue'
import ControlPanel from './ControlPanel.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: BookingRooms,
  rootContainer: '#booking-rooms',
})

createVueInstance({
  rootComponent: ControlPanel,
  rootContainer: '#booking-control-panel',
})
