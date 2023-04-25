<script lang="ts" setup>
import { computed, ref } from 'vue'

import checkIcon from '@mdi/svg/svg/check.svg'
import pencilIcon from '@mdi/svg/svg/pencil.svg'
import groupBy from 'lodash/groupBy'
import { DateTime } from 'luxon'

import BaseButton from '~resources/components/BaseButton.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
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

const editable = ref(false)

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
  <BaseLayout v-if="isHotelFetching" title="Загрузка…" />
  <BaseLayout v-else-if="hotel !== null" :title="hotel.name">
    <template #header-controls>
      <BaseButton
        :label="editable ? 'Готово' : 'Редактировать'"
        :start-icon="editable ? checkIcon : pencilIcon"
        @click="editable = !editable"
      />
    </template>
    <div class="quotasTables">
      <quotas-table
        v-for="{ id, monthDate, quotas } in months"
        :key="id"
        :month="monthDate"
        :quotas="quotas"
        :editable="editable"
      />
    </div>
  </BaseLayout>
</template>
<style lang="scss" scoped>
.quotasTables {
  display: flex;
  flex-flow: column;
  gap: 2em;
}
</style>
