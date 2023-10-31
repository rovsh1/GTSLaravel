import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import ServiceForm from './ServiceForm.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: ServiceForm,
  rootContainer: '#service-form',
  plugins: [pinia],
})
