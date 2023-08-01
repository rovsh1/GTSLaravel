import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import ActionsMenu from './ActionsMenu.vue'
import BookingRooms from './BookingRooms.vue'
import ControlPanel from './ControlPanel.vue'
import CopyButton from './CopyButton.vue'

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
  rootComponent: CopyButton,
  rootContainer: '#booking-copy-button',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: ActionsMenu,
  rootContainer: '#booking-actions-menu',
  plugins: [pinia],
})
