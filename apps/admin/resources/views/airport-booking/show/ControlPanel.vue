<script setup lang="ts">

import { computed } from 'vue'

import { useToggle } from '@vueuse/core'

import { useBookingStore } from '~resources/views/airport-booking/show/store/booking'
import { useBookingRequestStore } from '~resources/views/airport-booking/show/store/request'
import { useBookingStatusHistoryStore } from '~resources/views/airport-booking/show/store/status-history'
import ControlPanelSection from '~resources/views/hotel-booking/show/components/ControlPanelSection.vue'
import StatusHistoryModal from '~resources/views/hotel-booking/show/components/StatusHistoryModal.vue'
import StatusSelect from '~resources/views/hotel-booking/show/components/StatusSelect.vue'
import { getCancelPeriodTypeName, getHumanRequestType } from '~resources/views/hotel-booking/show/lib/constants'

import { CancelConditions } from '~api/booking/hotel/details'
import { BookingRequest } from '~api/booking/hotel/request'
import { BookingAvailableActionsResponse } from '~api/booking/hotel/status'
import { BookingStatusResponse } from '~api/booking/models'

import { formatDate, formatDateTime } from '~lib/date'

const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)
console.log(isHistoryModalOpened)

const bookingStore = useBookingStore()
const { fetchBooking, fetchAvailableActions } = bookingStore
const booking = computed(() => bookingStore.booking)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)

// const cancelConditions = computed<CancelConditions | null>(
// () => bookingStore.booking?.cancelConditions || null)

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore

const requestStore = useBookingRequestStore()
const bookingRequests = computed<BookingRequest[] | null>(() => requestStore.requests)

const cancelConditions = computed<CancelConditions | null>(() => bookingStore.booking?.cancelConditions || null)

// flags
// const isRoomsAndGuestsFilled = computed<boolean>(() =>
//! bookingStore.isEmptyGuests && !bookingStore.isEmptyRooms)
const isRoomsAndGuestsFilled = computed<boolean>(() => true)
const isRequestableStatus = computed<boolean>(() => availableActions.value?.isRequestable || false)
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)
const isRequestFetching = computed(() => requestStore.requestSendIsFetching)

const canSendCancellationRequest = computed<boolean>(() => availableActions.value?.canSendCancellationRequest || false)
const canSendBookingRequest = computed<boolean>(() => availableActions.value?.canSendBookingRequest || false)
const canSendChangeRequest = computed<boolean>(() => availableActions.value?.canSendChangeRequest || false)

const handleStatusChange = async (value: number) => {
  await bookingStore.changeStatus(value)
  await fetchStatusHistory()
}

const handleRequestSend = async () => {
  await requestStore.sendRequest()
  await Promise.all([
    fetchAvailableActions(),
    fetchBooking(),
    fetchStatusHistory(),
  ])
}

</script>

<template>
  <StatusHistoryModal
    :opened="isHistoryModalOpened"
    :is-fetching="statusHistoryStore.isFetching"
    :status-history-events="statusHistoryStore.statusHistoryEvents"
    @close="toggleHistoryModal(false)"
    @refresh="fetchStatusHistory"
  />

  <div class="d-flex flex-wrap flex-grow-1 gap-2">
    <StatusSelect
      v-if="booking && statuses"
      v-model="booking.status"
      :statuses="statuses"
      :available-statuses="availableActions?.statuses || null"
      :is-loading="isStatusUpdateFetching || isAvailableActionsFetching"
      @change="handleStatusChange"
    />
    <a href="#" class="btn-log" @click.prevent="toggleHistoryModal()">История изменений</a>
  </div>

  <ControlPanelSection
    title="Запросы на бронирование услуги"
    class="mt-4"
  >
    <div class="reservation-requests mb-2">
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
        <a
          href="#"
          class="btn-download"
          @click.prevent="requestStore.downloadDocument(bookingRequest.id)"
        >Скачать</a>
      </div>
    </div>

    <div v-if="isRequestableStatus">
      <div v-if="isRoomsAndGuestsFilled">
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
        text="Для отправки запроса необходимо заполнить информацию по номерам и гостям"
      />
    </div>
  </ControlPanelSection>

  <ControlPanelSection title="Условия отмены" class="mt-4">
    <table class="table-params">
      <tbody>
        <tr>
          <th>Отмена без штрафа</th>
          <td>до {{ cancelConditions?.cancelNoFeeDate ? formatDate(cancelConditions.cancelNoFeeDate) : '-' }}</td>
        </tr>
        <tr v-for="dailyMarkup in cancelConditions?.dailyMarkups" :key="dailyMarkup.daysCount">
          <th>За {{ dailyMarkup.daysCount }} дней</th>
          <td>
            {{ dailyMarkup.percent }}% {{ getCancelPeriodTypeName(dailyMarkup.cancelPeriodType) }}
          </td>
        </tr>
        <tr>
          <th>Незаезд</th>
          <td v-if="cancelConditions">
            {{ cancelConditions.noCheckInMarkup.percent }}%
            {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
          </td>
        </tr>
      </tbody>
    </table>
  </ControlPanelSection>
</template>
