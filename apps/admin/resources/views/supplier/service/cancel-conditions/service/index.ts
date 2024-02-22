import { createPinia } from 'pinia'

import { createVueInstance } from '~resources/vue/vue'

import ServiceList from './components/ServiceList.vue'

import '~resources/views/main'

const pinia = createPinia()

createVueInstance({
  rootComponent: ServiceList,
  rootContainer: '#service-cancel-conditions',
  plugins: [pinia],
})
