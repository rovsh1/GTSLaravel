<script setup lang="ts">
import { computed } from 'vue'

import { z } from 'zod'

import StatusSelect from '~resources/views/booking/shared/components/StatusSelect.vue'
import { useExternalNumber } from '~resources/views/booking/shared/composables/external-number'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import { BookingAvailableActionsResponse } from '~api/booking/hotel/status'
import { BookingStatusResponse } from '~api/booking/models'

import { isInitialDataExists, requestInitialData, ViewInitialDataKey } from '~lib/initial-data'

let isHotelBooking = true
let initialDataKey: ViewInitialDataKey = 'view-initial-data-hotel-booking'
if (isInitialDataExists('view-initial-data-service-booking')) {
  isHotelBooking = false
  initialDataKey = 'view-initial-data-service-booking'
}

const { bookingID } = requestInitialData(initialDataKey, z.object({
  bookingID: z.number(),
}))

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore
const bookingStore = useBookingStore()

const booking = computed(() => bookingStore.booking)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)

const handleStatusChange = async (value: number) => {
  if (isHotelBooking) {
    const { hideValidation } = useExternalNumber(bookingID)
    hideValidation()
  }
  await bookingStore.changeStatus(value)
  await fetchStatusHistory()
}

</script>

<template>
  <StatusSelect
    v-if="booking && statuses"
    v-model="booking.status"
    :statuses="statuses"
    :available-statuses="availableActions?.statuses || null"
    :is-loading="isStatusUpdateFetching || isAvailableActionsFetching"
    @change="handleStatusChange"
  />
</template>
