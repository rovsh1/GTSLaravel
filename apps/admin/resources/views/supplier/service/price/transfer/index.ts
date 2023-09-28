import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import TransferPrices from './TransferPrices.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: TransferPrices,
  rootContainer: '#transfer-prices',
  plugins: [pinia],
})
