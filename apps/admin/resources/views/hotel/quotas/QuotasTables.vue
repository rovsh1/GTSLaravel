<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import checkIcon from '@mdi/svg/svg/check.svg'
import pencilIcon from '@mdi/svg/svg/pencil.svg'
import groupBy from 'lodash/groupBy'
import { DateTime } from 'luxon'

import BaseLayout from '~resources/components/BaseLayout.vue'
import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import LoadingSpinner from '~resources/components/LoadingSpinner.vue'
import { useHotelAPI, useHotelQuotasAPI, useHotelRoomsListAPI } from '~resources/lib/api/hotel'
import { Hotel, Room } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'

import QuotasFilters from './components/QuotasFilters/QuotasFilters.vue'
import QuotasTable from './components/QuotasTable.vue'

import { quotasMock } from './components/lib/mock'
import { defaultFiltersPayload, FiltersPayload, intervalByMonthsCount } from './components/QuotasFilters/lib'

const { hotel: hotelID } = useUrlParams()

const {
  data: hotelData,
  execute: fetchHotel,
  isFetching: isHotelFetching,
} = useHotelAPI({ hotelID })

fetchHotel()

const hotel = computed<Hotel | null>(() => hotelData.value)

const {
  data: roomsData,
  execute: fetchHotelRoomsAPI,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI(computed(() => ({ hotelID })))

const rooms = computed<Room[] | null>(() => roomsData.value)

fetchHotelRoomsAPI()

const filtersPayload = ref<FiltersPayload>(defaultFiltersPayload)

const {
  isFetching: isHotelQuotasFetching,
  execute: fetchHotelQuotas,
} = useHotelQuotasAPI(computed(() => {
  const { month, year, monthsCount, availability, room } = filtersPayload.value
  return {
    hotelID,
    month,
    year,
    interval: intervalByMonthsCount[monthsCount],
    roomID: room,
    availability,
  }
}))

fetchHotelQuotas()

watch(filtersPayload, () => fetchHotelQuotas())

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

const handleFilters = (value: FiltersPayload) => {
  filtersPayload.value = value
}
</script>
<template>
  <BaseLayout
    :title="hotel?.name ?? ''"
    :loading="isHotelFetching || isHotelRoomsFetching"
  >
    <template #header-controls>
      <BootstrapButton
        :label="editable ? 'Готово' : 'Редактировать'"
        :start-icon="editable ? checkIcon : pencilIcon"
        severity="primary"
        @click="editable = !editable"
      />
    </template>
    <div class="quotasBody">
      <QuotasFilters
        v-if="rooms"
        :rooms="rooms"
        :loading="isHotelQuotasFetching"
        @submit="value => handleFilters(value)"
      />
      <LoadingSpinner v-if="isHotelQuotasFetching" />
      <div v-else class="quotasTables">
        <quotas-table
          v-for="{ id, monthDate, quotas } in months"
          :key="id"
          :month="monthDate"
          :quotas="quotas"
          :editable="editable"
        />
      </div>
    </div>
  </BaseLayout>
</template>
<style lang="scss" scoped>
.quotasBody {
  display: flex;
  flex-flow: column;
  gap: 2em;
}

.quotasTables {
  display: flex;
  flex-flow: column;
  gap: 2em;
}
</style>
