import { createPinia } from 'pinia'

import { createVueInstance } from '~resources/vue/vue'

import TransferPrices from './TransferPrices.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: TransferPrices,
  rootContainer: '#transfer-prices',
  plugins: [pinia],
})
