<script lang="ts" setup>
import { computed, nextTick, ref, watch, watchEffect } from 'vue'

import checkIcon from '@mdi/svg/svg/check.svg'
import pencilIcon from '@mdi/svg/svg/pencil.svg'
import { z } from 'zod'

import { createHotelSwitcher } from '~resources/lib/hotel-switcher/hotel-switcher'

import { HotelResponse, useHotelGetAPI } from '~api/hotel/get'
import { useHotelQuotasAPI } from '~api/hotel/quotas/list'
import { UseHotelRooms, useHotelRoomsListAPI } from '~api/hotel/rooms'

import { injectInitialData } from '~lib/vue'

import BaseLayout from '~components/BaseLayout.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import LoadingSpinner from '~components/LoadingSpinner.vue'

import QuotasFilters from './components/QuotasFilters/QuotasFilters.vue'
import RoomQuotasComponent from './components/RoomQuotas.vue'

import { getRoomQuotas, RoomQuotas } from './components/lib'
import { defaultFiltersPayload, FiltersPayload, intervalByMonthsCount } from './components/QuotasFilters/lib'

const { hotelID } = injectInitialData(z.object({
  hotelID: z.number(),
}))
const openingDayMenuRoomId = ref<number | null>(null)

const {
  data: hotelData,
  execute: fetchHotel,
  isFetching: isHotelFetching,
} = useHotelGetAPI({ hotelID })

fetchHotel()

const hotel = computed<HotelResponse | null>(() => hotelData.value)

const {
  data: roomsData,
  execute: fetchHotelRoomsAPI,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI({ hotelID })

const rooms = computed<UseHotelRooms>(() => roomsData.value)

fetchHotelRoomsAPI()

const filtersPayload = ref<FiltersPayload>(defaultFiltersPayload)
const waitLoadAndRedrawData = ref<boolean>(false)
const updatedRoomID = ref<number | null>(null)

const {
  execute: fetchHotelQuotas,
  data: hotelQuotas,
} = useHotelQuotasAPI(computed(() => {
  const { month, year, monthsCount, availability } = filtersPayload.value
  return {
    hotelID,
    month,
    year,
    interval: intervalByMonthsCount[monthsCount],
    roomID: undefined,
    availability: availability ?? undefined,
  }
}))

const roomsQuotas = ref<RoomQuotas[] | null>(null)

const fetchHotelQuotasWrapper = async () => {
  waitLoadAndRedrawData.value = true
  try {
    await fetchHotelQuotas()
    roomsQuotas.value = getRoomQuotas({
      rooms: rooms.value,
      filters: filtersPayload.value,
      quotas: hotelQuotas.value,
    })
    nextTick(() => {
      waitLoadAndRedrawData.value = false
      updatedRoomID.value = null
    })
  } catch (error) {
    nextTick(() => {
      waitLoadAndRedrawData.value = false
      updatedRoomID.value = null
    })
  }
}

fetchHotelQuotasWrapper()

watch(filtersPayload, () => fetchHotelQuotasWrapper())

const editable = ref(false)

const activeRoomID = ref<number | null>(null)

watch(editable, (value) => {
  if (value === false) {
    fetchHotelQuotasWrapper()
  }
})

const handleFilters = (value: FiltersPayload) => {
  filtersPayload.value = value
}

watchEffect(() => {
  if (!isHotelFetching.value && !isHotelRoomsFetching.value) {
    nextTick(() => {
      createHotelSwitcher(document.getElementsByClassName('content-header')[0], false)
    })
  }
})
</script>
<template>
  <div id="hotel-quotas-wrapper">
    <BaseLayout :loading="isHotelFetching || isHotelRoomsFetching">
      <template #title>
        <div class="title">{{ hotel?.name ?? '' }}</div>
      </template>
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
          :loading="waitLoadAndRedrawData"
          @submit="value => handleFilters(value)"
          @switch-room="(value: number | null) => activeRoomID = value"
        />
        <LoadingSpinner v-if="waitLoadAndRedrawData && roomsQuotas === null" />
        <div v-else-if="hotel === null">
          Не удалось найти данные для отеля.
        </div>
        <div v-else-if="roomsQuotas === null">
          Не удалось найти комнаты для этого отеля.
        </div>
        <div v-else class="quotasTables">
          <template v-for="{ room, monthlyQuotas } in roomsQuotas">
            <RoomQuotasComponent
              v-if="room.id === activeRoomID || activeRoomID === null"
              :key="room.id"
              :hotel="hotel"
              :room="room"
              :monthly-quotas="monthlyQuotas"
              :editable="editable"
              :waiting-load-data="(updatedRoomID === room.id) ? waitLoadAndRedrawData : false"
              :opening-day-menu-room-id="openingDayMenuRoomId"
              @open-day-menu-in-another-room="(value: number | null) => {
                openingDayMenuRoomId = value
              }"
              @update="(updatedRoomIDParam: number) => {
                updatedRoomID = updatedRoomIDParam
                fetchHotelQuotasWrapper()
              }"
            />
          </template>
        </div>
      </div>
    </BaseLayout>
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

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

#hotel-quotas-wrapper {
  position: relative;
}
</style>
