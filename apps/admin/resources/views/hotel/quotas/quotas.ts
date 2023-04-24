import { createApp, h } from 'vue'

import QuotasTables from './QuotasTables.vue'

import '~resources/views/main'

createApp({
  render: () => h(QuotasTables),
}).mount('#quotas-tables')
