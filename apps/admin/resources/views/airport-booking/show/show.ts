import { createPinia } from 'pinia'

import EditableManager from '~resources/views/booking/EditableManager.vue'
import EditableNote from '~resources/views/booking/EditableNote.vue'

import { createVueInstance } from '~lib/vue'

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
