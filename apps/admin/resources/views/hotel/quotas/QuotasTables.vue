<script lang="ts" setup>
import { computed } from 'vue'

import groupBy from 'lodash/groupBy'
import { DateTime } from 'luxon'

import QuotasTable from './QuotasTable.vue'

import { quotasMock } from './lib/mock'

const months = computed(() => {
  const grouped = groupBy(quotasMock, ({ date }) => {
    const [year, month] = date.split('-')
    return `${year}-${month}`
  })

  return Object.keys(grouped).map((key) => ({
    id: key,
    monthDate: DateTime.fromFormat(key, 'yyyy-MM').toJSDate(),
    quotas: grouped[key],
  }))
})
</script>
<template>
  <div class="quotasTables">
    <quotas-table
      v-for="{ id, monthDate, quotas } in months"
      :key="id"
      :month="monthDate"
      :quotas="quotas"
    />
  </div>
</template>
<style lang="scss" scoped>
.quotasTables {
  display: flex;
  flex-flow: column;
  gap: 2em;
}
</style>
