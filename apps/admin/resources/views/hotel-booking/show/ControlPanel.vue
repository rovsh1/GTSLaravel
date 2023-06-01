<script setup lang="ts">

import { computed, reactive, ref } from 'vue'

import { z } from 'zod'

import RequestBlock from '~resources/views/hotel-booking/show/components/RequestBlock.vue'
import { externalNumberTypeOptions, getCancelPeriodTypeName } from '~resources/views/hotel-booking/show/constants'
import { useBookingStore } from '~resources/views/hotel-booking/show/store'

import { Booking, updateBookingStatus, updateExternalNumber, useGetBookingAPI } from '~api/booking'
import { CancelConditions, ExternalNumber, ExternalNumberType, ExternalNumberTypeEnum } from '~api/booking/details'
import { sendBookingRequest } from '~api/booking/request'
import { useBookingAvailableStatusesAPI, useBookingStatusesAPI } from '~api/booking/status'

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
const { fetchBookingDetails } = bookingStore

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
const { data: availableStatuses, execute: fetchAvailableStatuses } = useBookingAvailableStatusesAPI({ bookingID })

fetchStatuses()
fetchAvailableStatuses()
fetchBooking()

const booking = reactive<Booking>(bookingData as unknown as Booking)

// @todo прописать логику
const isRequestableStatus = computed<boolean>(() => bookingData.value?.status === 2)
const isRoomsAndGuestsFilled = computed<boolean>(() => !bookingStore.isEmptyGuests && !bookingStore.isEmptyRooms)

const handleStatusChange = async (value: number): Promise<void> => {
  await updateBookingStatus({ bookingID, status: value })
  fetchBooking()
  fetchAvailableStatuses()
}

const isRequestFetching = ref<boolean>(false)
const handleRequestSend = async () => {
  isRequestFetching.value = true
  await sendBookingRequest({ bookingID })
  await fetchBookingDetails()
  isRequestFetching.value = false
}

const handleUpdateExternalNumber = async () => {
  await updateExternalNumber({ bookingID, ...externalNumberData.value })
  await fetchBookingDetails()
  isExternalNumberChanged.value = false
}

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2">
    <StatusSelect
      v-if="booking && statuses"
      v-model="booking.status"
      :statuses="statuses"
      :available-statuses="availableStatuses"
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
    <div class="d-flex align-content-around">
      <div>
        <BootstrapSelectBase
          id="external_number_type"
          disabled-placeholder="Номер подтверждения брони в отеля"
          :value="externalNumberType as number"
          :options="externalNumberTypeOptions"
          @input="value => externalNumberType = value as 3 | 2 | 1"
        />
      </div>
      <div class="d-flex ml-2">
        <div class="external-number-wrapper">
          <input
            v-if="isNeedShowExternalNumber"
            v-model="externalNumber"
            class="form-control"
            type="text"
            placeholder="№ брони"
          >
          <div class="invalid-feedback">
            Пожалуйста, заполните номер брони.
          </div>
        </div>
        <div v-if="isExternalNumberChanged" class="ml-2">
          <a href="#" class="btn btn-primary" @click.prevent="handleUpdateExternalNumber">Сохранить</a>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h6>Финансовая стоимость брони</h6>
    <hr>
  </div>

  <div v-if="isRequestableStatus" class="mt-4">
    <h6>Запросы в гостиницу</h6>
    <hr>
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

  <div class="mt-4">
    <h6>Условия отмены</h6>
    <hr>
    <table class="table-params">
      <tbody>
        <tr>
          <th>Отмена без штрафа</th>
          <td>до {{ cancelConditions?.cancelNoFeeDate || '-' }}</td>
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
</style>
