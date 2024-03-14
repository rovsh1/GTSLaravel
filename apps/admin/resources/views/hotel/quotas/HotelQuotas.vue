<script lang="ts" setup>
import { computed, nextTick, ref, watchEffect } from 'vue'

import { formatDateToAPIDate } from 'gts-common/helpers/date'
import { requestInitialData } from 'gts-common/helpers/initial-data'
import { z } from 'zod'

import { useHotelGetAPI } from '~api/hotel/get'
import { useUpdateHotelRoomQuotasBatch } from '~api/hotel/quotas/batch'
import { useHotelQuotasAPI } from '~api/hotel/quotas/list'
import { HotelRoomQuotasStatusUpdateProps, useHotelRoomQuotasStatusUpdate } from '~api/hotel/quotas/status'
import { HotelRoomQuotasUpdateProps, useHotelRoomQuotasUpdate } from '~api/hotel/quotas/update'
import { useHotelRoomsListAPI } from '~api/hotel/rooms'

import { createHotelSwitcher } from '~widgets/hotel-switcher/hotel-switcher'

import QuotasComponent from './QuotasComponent.vue'

import { Day, getRoomQuotas, Month, RoomQuota } from './components/lib'
import { QuotasStatusUpdatePayload } from './components/lib/types'
import { defaultFiltersPayload, FiltersPayload } from './components/QuotasFilters/lib'

const { hotelID } = requestInitialData(z.object({
  hotelID: z.number(),
}))

const {
  data: hotelData,
  execute: fetchHotel,
  isFetching: isHotelFetching,
} = useHotelGetAPI({ hotelID })

fetchHotel()

const {
  data: roomsData,
  execute: fetchHotelRoomsAPI,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI({ hotelID })

fetchHotelRoomsAPI()

const filtersQuotasStatusBatchPayload = ref<QuotasStatusUpdatePayload | null>(null)
const filtersPayload = ref<FiltersPayload>(defaultFiltersPayload)
const waitLoadAndRedrawData = ref<boolean>(false)
const hotelRoomQuotasUpdateProps = ref<HotelRoomQuotasUpdateProps | null>(null)
const updateRoomQuotasStatusPayload = ref<HotelRoomQuotasStatusUpdateProps | null>(null)

const {
  execute: executeHotelRoomQuotasStatusUpdate,
  data: hotelRoomQuotasStatusUpdateData,
  isFetching: isHotelRoomQuotasStatusUpdateFetching,
} = useHotelRoomQuotasStatusUpdate(updateRoomQuotasStatusPayload)

const isSuccessUpdateRoomQuotasStatus = computed<boolean>(() => !!hotelRoomQuotasStatusUpdateData.value?.success)

const {
  execute: executeHotelRoomQuotasUpdate,
  data: hotelRoomQuotasUpdateData,
  isFetching: isHotelRoomQuotasUpdateFetching,
} = useHotelRoomQuotasUpdate(hotelRoomQuotasUpdateProps)

const updatedQuotasRoomId = computed<number | null>(() => (hotelRoomQuotasUpdateData.value?.success
  ? hotelRoomQuotasUpdateProps.value?.roomID || null : null))

const {
  execute: fetchHotelQuotas,
  data: hotelQuotas,
} = useHotelQuotasAPI(computed(() => {
  const { dateFrom, dateTo, availability } = filtersPayload.value
  return {
    hotelID,
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
    roomID: undefined,
    availability: availability ?? undefined,
  }
}))

const {
  execute: executeUpdateHotelRoomQuotasBatch,
  isFetching: isUpdateHotelRoomQuotasBatch,
} = useUpdateHotelRoomQuotasBatch(computed(() => {
  if (!filtersQuotasStatusBatchPayload.value) return null
  const { dateFrom, dateTo, selectedRoomsID, daysWeekSelected, action } = filtersQuotasStatusBatchPayload.value
  return {
    hotelID,
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
    weekDays: daysWeekSelected,
    roomIds: selectedRoomsID,
    action,
  }
}))

const quotasPeriod = ref<Day[]>([])
const quotasPeriodMonths = ref<Month[]>([])
const allQuotas = ref<Map<string, RoomQuota>>(new Map<string, RoomQuota>([]))

const fetchHotelQuotasWrapper = async (filters: FiltersPayload) => {
  filtersPayload.value = filters
  waitLoadAndRedrawData.value = true
  try {
    await fetchHotelQuotas()
    const roomsQuotasAccumalationData = getRoomQuotas({
      filters: filtersPayload.value,
      quotas: hotelQuotas.value,
    })
    const { period, months, quotas } = roomsQuotasAccumalationData
    quotasPeriod.value = period
    quotasPeriodMonths.value = months
    allQuotas.value = quotas
    nextTick(() => {
      waitLoadAndRedrawData.value = false
    })
  } catch (error) {
    nextTick(() => {
      waitLoadAndRedrawData.value = false
    })
  }
}

fetchHotelQuotasWrapper(filtersPayload.value)

watchEffect(() => {
  if (!isHotelFetching.value && !isHotelRoomsFetching.value) {
    nextTick(() => {
      createHotelSwitcher(document.getElementsByClassName('content-header')[0], false)
    })
  }
})

const handleUpdateQuotasBatch = async (batchFilters: QuotasStatusUpdatePayload, filters: FiltersPayload) => {
  if (!batchFilters || isUpdateHotelRoomQuotasBatch.value) return
  filtersQuotasStatusBatchPayload.value = batchFilters
  filtersPayload.value = filters
  await executeUpdateHotelRoomQuotasBatch()
  fetchHotelQuotasWrapper(filtersPayload.value)
}

const handleUpdateRoomQuotas = async (updatingQuotasPayload: HotelRoomQuotasUpdateProps | null) => {
  if (!updatingQuotasPayload) return
  hotelRoomQuotasUpdateData.value = null
  hotelRoomQuotasUpdateProps.value = updatingQuotasPayload
  executeHotelRoomQuotasUpdate()
}

const handleUpdateRoomQuotasStatus = async (updatingQuotasStatusPayload: HotelRoomQuotasStatusUpdateProps | null) => {
  hotelRoomQuotasStatusUpdateData.value = null
  updateRoomQuotasStatusPayload.value = updatingQuotasStatusPayload
  executeHotelRoomQuotasStatusUpdate()
}

</script>
<template>
  <div id="hotel-quotas-wrapper">
    <QuotasComponent
      :is-hotel-fetching="isHotelFetching"
      :is-hotel-rooms-fetching="isHotelRoomsFetching"
      :is-update-hotel-room-quotas-batch="isUpdateHotelRoomQuotasBatch"
      :is-hotel-room-quotas-update-fetching="isHotelRoomQuotasUpdateFetching"
      :is-update-room-quotas-status="isHotelRoomQuotasStatusUpdateFetching"
      :is-success-update-room-quotas-status="isSuccessUpdateRoomQuotasStatus"
      :updated-quotas-room-id="updatedQuotasRoomId"
      :wait-load-and-redraw-data="waitLoadAndRedrawData"
      :hotel="hotelData"
      :rooms="roomsData || []"
      :days="quotasPeriod"
      :months="quotasPeriodMonths"
      :all-quotas="allQuotas"
      @fetch-hotel-quotas="fetchHotelQuotasWrapper"
      @update-hotel-room-quotas-batch="handleUpdateQuotasBatch"
      @room-quotas-update="handleUpdateRoomQuotas"
      @room-quotas-status-update="handleUpdateRoomQuotasStatus"
    />
  </div>
</template>
<style lang="scss" scoped>
#hotel-quotas-wrapper {
  position: relative;
}
</style>
