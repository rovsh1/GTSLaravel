import { createApp, h } from 'vue'

import QuotasTable from '~resources/views/hotel/quotas/QuotasTable.vue'

createApp({
  render: () => h(QuotasTable),
}).mount('#quotas-table')
