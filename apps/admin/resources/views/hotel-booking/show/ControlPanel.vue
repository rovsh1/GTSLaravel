<script setup lang="ts">

import { computed, reactive, ref } from 'vue'

import { z } from 'zod'

import RequestBlock from '~resources/views/hotel-booking/show/components/RequestBlock.vue'
import { externalNumberTypeOptions, getCancelPeriodTypeName } from '~resources/views/hotel-booking/show/constants'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'
import { useBookingRequestStore } from '~resources/views/hotel-booking/show/store/request'

import { Booking, updateBookingStatus, updateExternalNumber, useGetBookingAPI } from '~api/booking'
import { CancelConditions, ExternalNumber, ExternalNumberType, ExternalNumberTypeEnum } from '~api/booking/details'
import { BookingRequest, downloadRequestDocument, sendBookingRequest } from '~api/booking/request'
import { BookingAvailableActionsResponse, useBookingStatusesAPI } from '~api/booking/status'

import { formatDate, formatDateTime } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

import StatusSelect from './components/StatusSelect.vue'

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const bookingStore = useBookingStore()
const { fetchBookingDetails, fetchAvailableActions } = bookingStore
const requestStore = useBookingRequestStore()
const { fetchBookingRequests } = requestStore

const externalNumberData = ref<ExternalNumber>({
  type: ExternalNumberTypeEnum.HotelBookingNumber,
  number: null,
})

const isExternalNumberChanged = ref<boolean>(false)

const externalNumberType = computed<ExternalNumberType>({
  get: () => {
    if (isExternalNumberChanged.value) {
      return externalNumberData.value.type
    }
    return bookingStore.bookingDetails?.additionalInfo?.externalNumber?.type || ExternalNumberTypeEnum.HotelBookingNumber
  },
  set: (value: ExternalNumberType) => {
    isExternalNumberChanged.value = true
    externalNumberData.value.type = value
    externalNumberData.value.number = null
  },
})

// @todo валидация перед переходом на статус "Подтверждена" для админки отелей.
// @todo валидация перед отправкой ваучера для админки GTS.
const externalNumber = computed<string | null>({
  get: () => {
    if (isExternalNumberChanged.value) {
      return externalNumberData.value.number
    }
    return bookingStore.bookingDetails?.additionalInfo?.externalNumber?.number || null
  },
  set: (value: string | null): void => {
    isExternalNumberChanged.value = true
    externalNumberData.value.number = value
  },
})
const isNeedShowExternalNumber = computed<boolean>(
  () => Number(externalNumberType.value) === ExternalNumberTypeEnum.HotelBookingNumber,
)

const cancelConditions = computed<CancelConditions | null>(() => bookingStore.bookingDetails?.cancelConditions || null)

const { data: bookingData, execute: fetchBooking } = useGetBookingAPI({ bookingID })
const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()

const booking = reactive<Booking>(bookingData as unknown as Booking)

const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isRequestableStatus = computed<boolean>(() => availableActions.value?.isRequestable || false)
const canSendClientVoucher = computed<boolean>(() => availableActions.value?.canSendVoucher || false)
const canSendCancellationRequest = computed<boolean>(() => availableActions.value?.canSendCancellationRequest || false)
const canSendBookingRequest = computed<boolean>(() => availableActions.value?.canSendBookingRequest || false)
const canSendChangeRequest = computed<boolean>(() => availableActions.value?.canSendChangeRequest || false)
const canEditExternalNumber = computed<boolean>(() => availableActions.value?.canEditExternalNumber || false)
const isRoomsAndGuestsFilled = computed<boolean>(() => !bookingStore.isEmptyGuests && !bookingStore.isEmptyRooms)
const bookingRequests = computed<BookingRequest[] | null>(() => requestStore.requests)

const isStatusUpdateFetching = ref(false)
const handleStatusChange = async (value: number): Promise<void> => {
  isStatusUpdateFetching.value = true
  await updateBookingStatus({ bookingID, status: value })
  await Promise.all([
    fetchBooking(),
    fetchAvailableActions(),
  ])
  isStatusUpdateFetching.value = false
}

const isRequestFetching = ref<boolean>(false)
const handleRequestSend = async () => {
  isRequestFetching.value = true
  await sendBookingRequest({ bookingID })
  await Promise.all([
    fetchAvailableActions(),
    fetchBooking(),
    fetchBookingDetails(),
    fetchBookingRequests(),
  ])
  isRequestFetching.value = false
}

const isUpdateExternalNumberFetching = ref(false)
const handleUpdateExternalNumber = async () => {
  isUpdateExternalNumberFetching.value = true
  await updateExternalNumber({ bookingID, ...externalNumberData.value })
  await fetchBookingDetails()
  isExternalNumberChanged.value = false
  isUpdateExternalNumberFetching.value = false
}

const handleDownloadDocument = async (requestId: number): Promise<void> => {
  await downloadRequestDocument({ requestID: requestId, bookingID })
}

const getHumanRequestType = (type: number): string => {
  let preparedType = 'изменение'
  if (type === 1) {
    preparedType = 'бронирование'
  }
  if (type === 3) {
    preparedType = 'отмену'
  }

  return preparedType
}

fetchStatuses()
fetchAvailableActions()
fetchBooking()
fetchBookingRequests()

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2">
    <StatusSelect
      v-if="booking && statuses"
      v-model="booking.status"
      :statuses="statuses"
      :available-statuses="availableActions?.statuses || null"
      :is-loading="isStatusUpdateFetching || isAvailableActionsFetching"
      @change="handleStatusChange"
    />
    <a href="#" class="btn-log">История изменений</a>
    <div class="float-end">
      Общая сумма: <strong>0 <span class="cur">сўм</span></strong>
    </div>
  </div>

  <div class="mt-4">
    <h6>Тип номера подтверждения бронирования</h6>
    <hr>
    <div class="d-flex align-content-around" :class="{ loading: isUpdateExternalNumberFetching }">
      <div>
        <BootstrapSelectBase
          id="external_number_type"
          :disabled="!canEditExternalNumber"
          disabled-placeholder="Номер подтверждения брони в отеля"
          :value="externalNumberType as number"
          :options="externalNumberTypeOptions"
          @input="value => externalNumberType = value as ExternalNumberType"
        />
      </div>
      <div class="d-flex ml-2">
        <div class="external-number-wrapper">
          <input
            v-if="isNeedShowExternalNumber"
            v-model="externalNumber"
            class="form-control"
            :disabled="!canEditExternalNumber"
            type="text"
            placeholder="№ брони"
          >
          <div class="invalid-feedback">
            Пожалуйста, заполните номер брони.
          </div>
        </div>
        <div v-if="isExternalNumberChanged" class="ml-2">
          <a href="#" class="btn btn-primary" @click.prevent="handleUpdateExternalNumber">
            Сохранить
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h6>Финансовая стоимость брони</h6>
    <hr>
  </div>

  <div class="mt-4">
    <h6>Запросы в гостиницу</h6>
    <hr>

    <div class="reservation-requests mb-2">
      <div
        v-for="bookingRequest in bookingRequests"
        :key="bookingRequest.id"
        class="d-flex flex-row justify-content-between w-100 py-1"
      >
        <div>
          Запрос на {{ getHumanRequestType(bookingRequest.type) }}
          <span class="date align-left ml-1">от {{ formatDateTime(bookingRequest.dateCreate) }}</span>
        </div>
        <a href="#" class="btn-download" @click.prevent="handleDownloadDocument(bookingRequest.id)">Скачать</a>
      </div>
    </div>

    <div v-if="isRequestableStatus">
      <div v-if="canSendBookingRequest">
        <RequestBlock
          v-if="isRoomsAndGuestsFilled"
          text="Запрос на бронирование еще не отправлен"
          :loading="isRequestFetching"
          @click="handleRequestSend"
        />
        <RequestBlock
          v-else
          :show-button="false"
          text="Для отправки запроса необходимо заполнить информацию по номерам и гостям"
        />
      </div>

      <RequestBlock
        v-if="canSendCancellationRequest"
        :loading="isRequestFetching"
        text="Бронирование подтверждено, до выставления счета доступен запрос на отмену"
        variant="danger"
        @click="handleRequestSend"
      />

      <RequestBlock
        v-if="canSendChangeRequest"
        :loading="isRequestFetching"
        text="Ожидание изменений и отправки запроса"
        variant="warning"
        @click="handleRequestSend"
      />
    </div>
  </div>

  <div v-if="canSendClientVoucher" class="mt-4">
    <h6>Файлы, отправленные клиенту</h6>
    <hr>
    <RequestBlock
      variant="success"
      text="При необходимости клиенту можно отправить ваучер"
    />
  </div>

  <div class="mt-4">
    <h6>Условия отмены</h6>
    <hr>
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
            {{ cancelConditions.noCheckInMarkup.percent }}% {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped lang="scss">
.ml-2 {
  margin-left: 0.5rem;
}

hr {
  margin: 0.5rem 0 0.75rem;
}

.reservation-requests {
  line-height: 18px;

  span.date {
    color: #b5b5c3;
    font-size: 11px;
  }

  .btn-download {
    font-size: 11px;
  }
}
</style>
