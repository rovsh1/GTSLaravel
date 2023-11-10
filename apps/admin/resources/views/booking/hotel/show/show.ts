import { createPinia } from 'pinia'

import ActionsMenu from '~resources/views/booking/shared/components/ActionsMenu.vue'
import ControlPanel from '~resources/views/booking/shared/components/BookingPanel/ControlPanel.vue'
import CopyButton from '~resources/views/booking/shared/components/CopyButton.vue'
import EditableManager from '~resources/views/booking/shared/components/EditableManager.vue'
import EditableNote from '~resources/views/booking/shared/components/EditableNote.vue'

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
  rootComponent: CopyButton,
  rootContainer: '#booking-copy-button',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: ActionsMenu,
  rootContainer: '#booking-actions-menu',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableNote,
  rootContainer: '#booking-editable-note',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableManager,
  rootContainer: '#booking-editable-manager',
  plugins: [pinia],
})
