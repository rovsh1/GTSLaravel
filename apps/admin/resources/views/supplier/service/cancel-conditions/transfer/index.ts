import { createPinia } from 'pinia'

import { createVueInstance } from '~lib/vue'

import ServiceList from './components/ServiceList.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: ServiceList,
  rootContainer: '#transfer-cancel-conditions',
  plugins: [pinia],
})
