import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import OtherPrices from './OtherPrices.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: OtherPrices,
  rootContainer: '#other-prices',
  plugins: [pinia],
})
