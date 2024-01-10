import { createPinia } from 'pinia'

import ActionsMenu from '~resources/views/booking/shared/components/ActionsMenu.vue'
import ControlPanel from '~resources/views/booking/shared/components/BookingPanel/ControlPanel.vue'
import CancelConditions from '~resources/views/booking/shared/components/CancelConditions.vue'

import { createVueInstance } from '~lib/vue'

import BookingRooms from './BookingRooms.vue'

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

createVueInstance({
  rootComponent: CancelConditions,
  rootContainer: '#booking-cancel-conditions',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: ActionsMenu,
  rootContainer: '#booking-actions-menu',
  plugins: [pinia],
})
