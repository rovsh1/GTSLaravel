<script setup lang="ts">

import { computed } from 'vue'

import { useToggle } from '@vueuse/core'

import { useBookingStore } from '~resources/views/airport-booking/show/store/booking'
import { useBookingStatusHistoryStore } from '~resources/views/airport-booking/show/store/status-history'
import ControlPanelSection from '~resources/views/hotel-booking/show/components/ControlPanelSection.vue'
import StatusHistoryModal from '~resources/views/hotel-booking/show/components/StatusHistoryModal.vue'
import StatusSelect from '~resources/views/hotel-booking/show/components/StatusSelect.vue'

import { BookingAvailableActionsResponse } from '~api/booking/hotel/status'
import { BookingStatusResponse } from '~api/booking/models'

const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)
console.log(isHistoryModalOpened)

const bookingStore = useBookingStore()
const booking = computed(() => bookingStore.booking)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)

// const cancelConditions = computed<CancelConditions | null>(
// () => bookingStore.booking?.cancelConditions || null)

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore

// flags
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)

const handleStatusChange = async (value: number) => {
  await bookingStore.changeStatus(value)
  await fetchStatusHistory()
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
    <!--<div class="reservation-requests mb-2">
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
        <a href="#" class="btn-download"
        @click.prevent="requestStore.downloadDocument(bookingRequest.id)">Скачать</a>
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
    </div>-->
  </ControlPanelSection>

  <ControlPanelSection title="Условия отмены" class="mt-4" />
</template>
