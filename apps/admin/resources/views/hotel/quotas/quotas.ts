import { createVueInstance } from '~lib/vue'

import QuotasTables from './QuotasTables.vue'

import '~resources/views/main'

createVueInstance({
  rootComponent: QuotasTables,
  rootContainer: '#quotas-tables',
})
