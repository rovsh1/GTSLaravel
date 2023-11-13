import { createPinia } from 'pinia'

import ActionsMenu from '~resources/views/booking-order/show/components/ActionsMenu.vue'
import CopyButton from '~resources/views/booking-order/show/components/CopyButton.vue'
import EditableManager from '~resources/views/booking-order/show/components/EditableManager.vue'
import EditableNote from '~resources/views/booking-order/show/components/EditableNote.vue'
import ControlPanel from '~resources/views/booking-order/show/components/OrderPanel/ControlPanel.vue'

import { createVueInstance } from '~lib/vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: ControlPanel,
  rootContainer: '#order-control-panel',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: CopyButton,
  rootContainer: '#order-copy-button',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: ActionsMenu,
  rootContainer: '#order-actions-menu',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableNote,
  rootContainer: '#order-editable-note',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableManager,
  rootContainer: '#order-editable-manager',
  plugins: [pinia],
})
