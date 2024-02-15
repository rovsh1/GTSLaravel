import { createPinia } from 'pinia'

import EditableExternalId from '~resources/views/booking-order/show/components/EditableExternalId.vue'
import EditableManager from '~resources/views/booking-order/show/components/EditableManager.vue'
import EditableNote from '~resources/views/booking-order/show/components/EditableNote.vue'
import ControlPanel from '~resources/views/booking-order/show/components/OrderPanel/ControlPanel.vue'
import { createVueInstance } from '~resources/vue/vue'

import OrderDetails from './OrderDetails.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: OrderDetails,
  rootContainer: '#order-details',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: ControlPanel,
  rootContainer: '#order-control-panel',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableNote,
  rootContainer: '#order-editable-note',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableNote,
  rootContainer: '#order-editable-note',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableExternalId,
  rootContainer: '#order-editable-external-id',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: EditableManager,
  rootContainer: '#order-editable-manager',
  plugins: [pinia],
})
