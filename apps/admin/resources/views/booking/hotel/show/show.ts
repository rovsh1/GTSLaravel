import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import BookingRooms from './BookingRooms.vue'
import ActionsMenu from './components/ActionsMenu.vue'
import CopyButton from './components/CopyButton.vue'
import EditableManager from './components/EditableManager.vue'
import EditableNote from './components/EditableNote.vue'
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
