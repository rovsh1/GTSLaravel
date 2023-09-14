import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import Details from './BookingDetails.vue'
import EditableManager from './components/EditableManager.vue'
import EditableNote from './components/EditableNote.vue'
import ControlPanel from './ControlPanel.vue'

import '~resources/views/main'

const pinia = createPinia()

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

createVueInstance({
  rootComponent: ControlPanel,
  rootContainer: '#booking-control-panel',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: Details,
  rootContainer: '#booking-details',
  plugins: [pinia],
})
