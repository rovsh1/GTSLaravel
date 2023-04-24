<script lang="ts" setup>
import { computed } from 'vue'

import groupBy from 'lodash/groupBy'
import { DateTime } from 'luxon'

import { useHotelAPI } from '~resources/lib/api/hotel'
import { Hotel } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'

import QuotasTable from './QuotasTable.vue'

import { quotasMock } from './lib/mock'

const { hotel: hotelID } = useUrlParams()

const {
  data: hotelData,
  execute: fetchHotel,
  isFetching: isHotelFetching,
} = useHotelAPI({ hotelID })

fetchHotel()

const hotel = computed<Hotel | null>(() => hotelData.value)

const months = computed(() => {
  const groupedByMonth = groupBy(quotasMock, ({ date }) => {
    const [year, month] = date.split('-')
    return `${year}-${month}`
  })

  return Object.keys(groupedByMonth).map((key) => ({
    id: key,
    monthDate: DateTime.fromFormat(key, 'yyyy-MM').toJSDate(),
    quotas: groupedByMonth[key],
  }))
})
</script>
<template>
  <div v-if="isHotelFetching">
    <div class="content-header">
      <div class="title">Загрузка…</div>
    </div>
  </div>
  <div v-else-if="hotel !== null">
    <div class="content-header">
      <div class="title">{{ hotel.name }}</div>
      <!-- TODO readonly toggle -->
    </div>
    <div class="content-body quotasTables">
      <quotas-table
        v-for="{ id, monthDate, quotas } in months"
        :key="id"
        :month="monthDate"
        :quotas="quotas"
      />
    </div>
  </div>
</template>
<style lang="scss" scoped>
.quotasTables {
  display: flex;
  flex-flow: column;
  gap: 2em;
}
</style>
