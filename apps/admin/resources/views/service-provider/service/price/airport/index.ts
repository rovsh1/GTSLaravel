import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import PricesTable from './PricesTable.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: PricesTable,
  rootContainer: '#prices-table',
  plugins: [pinia],
})
