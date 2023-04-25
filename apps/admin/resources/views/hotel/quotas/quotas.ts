import { createVueInstance } from '~resources/lib/vue'

import QuotasTables from './QuotasTables.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: QuotasTables,
  rootContainer: '#quotas-tables',
})
