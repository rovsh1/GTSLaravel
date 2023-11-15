<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { useExternalNumber } from '~resources/views/booking/shared/composables/external-number'
import { getHumanRequestType } from '~resources/views/booking/shared/lib/constants'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useBookingRequestStore } from '~resources/views/booking/shared/store/request'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import { BookingRequest } from '~api/booking/request'
import { BookingAvailableActionsResponse } from '~api/booking/status'

import { formatDateTime } from '~lib/date'

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore

const bookingStore = useBookingStore()
const { fetchBooking, fetchAvailableActions } = bookingStore
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)
const isRequestableStatus = computed<boolean>(() => availableActions.value?.isRequestable || false)

const requestStore = useBookingRequestStore()
const bookingRequests = computed<BookingRequest[] | null>(() => requestStore.groupedRequests)

const isRequestFetching = computed(() => requestStore.requestSendIsFetching)

const canSendCancellationRequest = computed<boolean>(() => availableActions.value?.canSendCancellationRequest || false)
const canSendBookingRequest = computed<boolean>(() => availableActions.value?.canSendBookingRequest || false)
const canSendChangeRequest = computed<boolean>(() => availableActions.value?.canSendChangeRequest || false)

const existGuests = computed<boolean>(() => bookingStore.existGuests)
const existCars = computed<boolean>(() => bookingStore.existCars)
const existRooms = computed<boolean>(() => bookingStore.existRooms)

const isGuestsFilled = computed<boolean>(() => !bookingStore.isEmptyGuests)
const isCarsFilled = computed<boolean>(() => !bookingStore.isEmptyCars)

const isRoomsFilled = computed<boolean>(() => !bookingStore.isEmptyRooms)
const isRoomsGuestsFilled = computed<boolean>(() => !bookingStore.isEmptyRoomsGuests)

const isRequestableData = computed(() => (existCars.value && isCarsFilled.value) || (existGuests.value && isGuestsFilled.value)
  || (existRooms.value && isRoomsFilled.value && isRoomsGuestsFilled.value)
  || (!existCars.value && !existRooms.value && !existGuests.value))

const requestableDataText = computed(() => {
  if (existCars.value && !isCarsFilled.value) {
    return 'о автомобилях'
  } if (existGuests.value && !isGuestsFilled.value) {
    return 'о гостях'
  } if (existRooms.value && (!isRoomsFilled.value || !isRoomsGuestsFilled.value)) {
    return 'по номерам и гостям '
  }
  return ''
})

const handleRequestSend = async () => {
  if (bookingStore.isHotelBooking && bookingStore.booking) {
    const { hideValidation } = useExternalNumber(bookingStore.booking.id)
    hideValidation()
  }
  await requestStore.sendRequest()
  await Promise.all([
    fetchAvailableActions(),
    fetchBooking(),
    fetchStatusHistory(),
  ])
}
</script>

<template>
  <div class="reservation-requests">
    <div
      v-for="bookingRequest in bookingRequests"
      :key="bookingRequest.id"
      class="d-flex flex-row justify-content-between w-100 py-1"
    >
      <div>
        Запрос на {{ getHumanRequestType(bookingRequest.type) }}
        <span class="date align-left ml-1">от
          {{ formatDateTime(bookingRequest.dateCreate) }}</span>
      </div>
      <a href="#" class="btn-download" @click.prevent="requestStore.downloadDocument(bookingRequest.id)">Скачать</a>
    </div>
  </div>

  <div v-if="isRequestableStatus" :class="{ 'mt-2': bookingRequests?.length }">
    <div
      v-if="isRequestableData"
    >
      <RequestBlock
        v-if="canSendBookingRequest"
        text="Запрос на бронирование еще не отправлен"
        :loading="isRequestFetching"
        @click="handleRequestSend"
      />

      <RequestBlock
        v-else-if="canSendCancellationRequest"
        :loading="isRequestFetching"
        text="Бронирование подтверждено, до выставления счета доступен запрос на отмену"
        variant="danger"
        @click="handleRequestSend"
      />

      <RequestBlock
        v-else-if="canSendChangeRequest"
        :loading="isRequestFetching"
        text="Ожидание изменений и отправки запроса"
        variant="warning"
        @click="handleRequestSend"
      />
    </div>
    <RequestBlock
      v-else
      :show-button="false"
      :text="`Для отправки запроса необходимо заполнить информацию ${requestableDataText}`"
    />
  </div>
  <div v-if="!isRequestableStatus && !bookingRequests?.length">
    <RequestBlock
      :show-button="false"
      text="Запросы поставщику не отправлялись"
    />
  </div>
</template>
