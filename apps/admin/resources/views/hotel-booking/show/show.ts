import { createVueInstance } from '~lib/vue'

import BookingRooms from './BookingRooms.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: BookingRooms,
  rootContainer: '#booking-rooms',
})
