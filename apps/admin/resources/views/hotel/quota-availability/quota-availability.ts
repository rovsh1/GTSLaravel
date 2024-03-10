import { createPinia } from 'pinia'

import { createVueInstance } from '~resources/vue/vue'

import QuotaAvailability from './QuotaAvailability.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: QuotaAvailability,
  rootContainer: '#quota-availability',
  plugins: [pinia],
})
