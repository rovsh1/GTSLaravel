<script setup lang="ts">
import { computed } from 'vue'

import StatusSelect from '~resources/views/booking/shared/components/StatusSelect.vue'
import { useExternalNumber } from '~resources/views/booking/shared/composables/external-number'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import { BookingStatusResponse } from '~api/booking/models'
import { BookingAvailableActionsResponse } from '~api/booking/status'

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore
const bookingStore = useBookingStore()

const booking = computed(() => bookingStore.booking)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)

const handleStatusChange = async (value: number) => {
  if (bookingStore.isHotelBooking && bookingStore.booking) {
    const { hideValidation } = useExternalNumber(bookingStore.booking.id)
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
