import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import AirportPrices from './AirportPrices.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: AirportPrices,
  rootContainer: '#airport-prices',
  plugins: [pinia],
})