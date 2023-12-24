import { createPinia } from 'pinia'

import CancellationConditions from '~resources/views/settings/CancellationConditions.vue'
import ResidenceConditions from '~resources/views/settings/ResidenceConditions.vue'

import { createVueInstance } from '~lib/vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: ResidenceConditions,
  rootContainer: '#residence-conditions',
  plugins: [pinia],
})

createVueInstance({
  rootComponent: CancellationConditions,
  rootContainer: '#cancellation-conditions',
  plugins: [pinia],
})
