<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import checkIcon from '@mdi/svg/svg/check.svg'
import pencilIcon from '@mdi/svg/svg/pencil.svg'

import BaseLayout from '~resources/components/BaseLayout.vue'
import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import LoadingSpinner from '~resources/components/LoadingSpinner.vue'
import { HotelResponse, useHotelAPI } from '~resources/lib/api/hotel/hotel'
import { useHotelQuotasAPI } from '~resources/lib/api/hotel/quotas'
import { UseHotelRooms, useHotelRoomsListAPI } from '~resources/lib/api/hotel/rooms'
import { useUrlParams } from '~resources/lib/url-params'

import QuotasFilters from './components/QuotasFilters/QuotasFilters.vue'
import QuotasTable from './components/QuotasTable.vue'

import { getRoomQuotas } from './components/lib'
import { defaultFiltersPayload, FiltersPayload, intervalByMonthsCount } from './components/QuotasFilters/lib'

const { hotel: hotelID } = useUrlParams()

const {
  data: hotelData,
  execute: fetchHotel,
  isFetching: isHotelFetching,
} = useHotelAPI({ hotelID })

fetchHotel()

const hotel = computed<HotelResponse | null>(() => hotelData.value)

const {
  data: roomsData,
  execute: fetchHotelRoomsAPI,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI(computed(() => ({ hotelID })))

const rooms = computed<UseHotelRooms>(() => roomsData.value)

fetchHotelRoomsAPI()

const filtersPayload = ref<FiltersPayload>(defaultFiltersPayload)

const {
  isFetching: isHotelQuotasFetching,
  execute: fetchHotelQuotas,
  data: hotelQuotas,
} = useHotelQuotasAPI(computed(() => {
  const { month, year, monthsCount, availability, roomID } = filtersPayload.value
  return {
    hotelID,
    month,
    year,
    interval: intervalByMonthsCount[monthsCount],
    roomID: roomID ?? undefined,
    availability: availability ?? undefined,
  }
}))

fetchHotelQuotas()

watch(filtersPayload, () => fetchHotelQuotas())

const editable = ref(false)

const roomsQuotas = computed(() => getRoomQuotas({
  rooms: rooms.value,
  filters: filtersPayload.value,
  quotas: hotelQuotas.value,
}))

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
        :disabled="roomsQuotas === null"
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
      <div v-else-if="roomsQuotas === null">
        Не удалось найти комнаты для этого отеля.
      </div>
      <div v-else class="quotasTables">
        <quotas-table
          v-for="{ room, monthlyQuotas } in roomsQuotas"
          :key="room.id"
          :room="room"
          :monthly-quotas="monthlyQuotas"
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
