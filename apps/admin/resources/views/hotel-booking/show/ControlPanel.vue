<script setup lang="ts">

import { computed, onMounted, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import PriceModal from '~resources/views/hotel-booking/show/components/PriceModal.vue'
import RequestBlock from '~resources/views/hotel-booking/show/components/RequestBlock.vue'
import StatusHistoryModal from '~resources/views/hotel-booking/show/components/StatusHistoryModal.vue'
import { externalNumberTypeOptions, getCancelPeriodTypeName } from '~resources/views/hotel-booking/show/constants'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order-currency'
import { useBookingRequestStore } from '~resources/views/hotel-booking/show/store/request'
import { useBookingStatusHistoryStore } from '~resources/views/hotel-booking/show/store/status-history'
import { useBookingVoucherStore } from '~resources/views/hotel-booking/show/store/voucher'

import { Booking } from '~api/booking'
import { CancelConditions, ExternalNumber, ExternalNumberType, ExternalNumberTypeEnum } from '~api/booking/details'
import { BookingRequest, downloadRequestDocument } from '~api/booking/request'
import { BookingAvailableActionsResponse, BookingStatusResponse } from '~api/booking/status'
import { Currency } from '~api/models'

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
const { fetchBooking, fetchAvailableActions } = bookingStore
const requestStore = useBookingRequestStore()
const { fetchBookingRequests } = requestStore
const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore
const orderStore = useOrderStore()
const voucherStore = useBookingVoucherStore()

const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)
const isRequestFetching = computed(() => requestStore.requestSendIsFetching)
const isUpdateExternalNumberFetching = computed(() => bookingStore.isUpdateExternalNumberFetching)
const isVoucherFetching = computed(() => voucherStore.voucherSendIsFetching)

const externalNumberData = ref<ExternalNumber>({
  type: ExternalNumberTypeEnum.HotelBookingNumber,
  number: null,
})

const isExternalNumberChanged = ref<boolean>(false)
const isExternalNumberInvalid = computed(() => !bookingStore.isExternalNumberValid)

const externalNumberType = computed<ExternalNumberType>({
  get: () => {
    if (isExternalNumberChanged.value) {
      return externalNumberData.value.type
    }
    return bookingStore.booking?.additionalInfo?.externalNumber?.type || ExternalNumberTypeEnum.HotelBookingNumber
  },
  set: (value: ExternalNumberType) => {
    isExternalNumberChanged.value = true
    externalNumberData.value.type = Number(value)
    externalNumberData.value.number = null
  },
})

const externalNumber = computed<string | null>({
  get: () => {
    if (isExternalNumberChanged.value) {
      return externalNumberData.value.number
    }
    return bookingStore.booking?.additionalInfo?.externalNumber?.number || null
  },
  set: (value: string | null): void => {
    isExternalNumberChanged.value = true
    externalNumberData.value.number = value
  },
})
const isNeedShowExternalNumber = computed<boolean>(
  () => Number(externalNumberType.value) === ExternalNumberTypeEnum.HotelBookingNumber,
)

const cancelConditions = computed<CancelConditions | null>(() => bookingStore.booking?.cancelConditions || null)

const booking = computed<Booking | null>(() => bookingStore.booking)
const orderCurrency = computed<Currency | undefined>(() => orderStore.currency)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
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
const lastHistoryItem = computed(() => statusHistoryStore.lastHistoryItem)
const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)
const [isNetPriceModalOpened, toggleNetPriceModal] = useToggle<boolean>(false)
const [isBruttoPriceModalOpened, toggleBruttoPriceModal] = useToggle<boolean>(false)

const handleStatusChange = async (value: number): Promise<void> => {
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

const handleUpdateExternalNumber = async () => {
  const isSuccess = await bookingStore.updateExternalNumber(externalNumberData.value)
  if (isSuccess) {
    isExternalNumberChanged.value = false
  }
}

const handleDownloadDocument = async (requestId: number): Promise<void> => {
  await downloadRequestDocument({
    requestID: requestId,
    bookingID,
  })
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

const handleVoucherSend = async () => {
  if (!bookingStore.validateExternalNumber(externalNumberType.value, externalNumber.value)) {
    return
  }
  if (isExternalNumberChanged.value) {
    await handleUpdateExternalNumber()
  }
  await voucherStore.sendVoucher()
}

onMounted(() => {
  fetchAvailableActions()
  fetchBookingRequests()
})

</script>

<template>
  <StatusHistoryModal
    :opened="isHistoryModalOpened"
    @close="toggleHistoryModal(false)"
  />

  <PriceModal
    header="Общая сумма (нетто)"
    label="Общая сумма (нетто) в UZS"
    :opened="isNetPriceModalOpened"
    @close="toggleNetPriceModal(false)"
  />

  <PriceModal
    header="Общая сумма (брутто)"
    :label="`Общая сумма (брутто) ${orderCurrency?.code_char}`"
    :opened="isBruttoPriceModalOpened"
    @close="toggleBruttoPriceModal(false)"
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
    <div v-if="booking && orderCurrency" class="float-end">
      Общая сумма:
      <strong>
        {{ booking.price.boValue.value }}
        <span class="cur">{{ orderCurrency.sign }}</span>
      </strong>
      <span v-if="booking.price.boValue.isManual" class="text-muted">(выставлена вручную)</span>
    </div>
  </div>

  <div class="mt-4">
    <h6>Тип номера подтверждения бронирования</h6>
    <hr>
    <div class="d-flex flex-row gap-2" :class="{ loading: isUpdateExternalNumberFetching }">
      <div class="w-50">
        <BootstrapSelectBase
          id="external_number_type"
          :disabled="!canEditExternalNumber"
          disabled-placeholder="Номер подтверждения брони в отеля"
          :value="externalNumberType as number"
          :options="externalNumberTypeOptions"
          @input="value => externalNumberType = value as ExternalNumberType"
        />
      </div>
      <div class="d-flex flex-row gap-2">
        <div v-if="isNeedShowExternalNumber" class="external-number-wrapper">
          <input
            v-model="externalNumber"
            class="form-control"
            :class="{ 'invalid-input': isExternalNumberInvalid }"
            :disabled="!canEditExternalNumber"
            type="text"
            placeholder="№ брони"
          >
          <div class="invalid-feedback" :class="{ 'd-block': isExternalNumberInvalid }">
            Пожалуйста, заполните номер брони.
          </div>
        </div>
        <div v-if="isExternalNumberChanged">
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
    <div class="d-flex flex-row gap-3">
      <div class="w-50 rounded shadow-lg p-3">
        <h6>Приход</h6>
        <hr>
        <div v-if="booking && orderCurrency">
          Общая сумма (брутто): {{ booking.price.boValue.value }}
          <span class="currency">{{ orderCurrency.sign }}</span>
        </div>
        <a href="#" @click.prevent="toggleBruttoPriceModal(true)">Изменить</a>
      </div>
      <div class="w-50 rounded shadow-lg p-3">
        <h6>Расход</h6>
        <hr>
        <div v-if="booking && orderCurrency">
          Общая сумма (нетто): {{ booking.price.hoValue.value }}
          <span class="currency">{{ orderCurrency.sign }}</span>
        </div>
        <a href="#" @click.prevent="toggleNetPriceModal(true)">Изменить</a>
      </div>
    </div>

    <div v-if="booking && orderCurrency" class="mt-2">
      Прибыль = {{ booking.price.boValue.value }} {{ orderCurrency.sign }} - {{ booking.price.hoValue.value }}
      {{ orderCurrency.sign }} = {{ booking.price.boValue.value - booking.price.hoValue.value }} {{
        orderCurrency.sign
      }}
    </div>

    <div v-if="lastHistoryItem && lastHistoryItem?.payload?.reason" class="mt-2 alert alert-warning" role="alert">
      {{ lastHistoryItem.payload.reason }}
    </div>
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
      :loading="isVoucherFetching"
      @click="handleVoucherSend"
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
            {{ cancelConditions.noCheckInMarkup.percent }}%
            {{ getCancelPeriodTypeName(cancelConditions.noCheckInMarkup.cancelPeriodType) }}
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

.alert {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  padding: 7px 140px 7px 16px;
  border-radius: 4px;
  color: black;

  &.alert-warning {
    background: #fff4de;

    a {
      background: #ffa800;

      &:hover {
        background-color: #FFCA2CFF;
      }
    }
  }
}

.invalid-input {
  border-color: var(--bs-form-invalid-border-color);
  padding-right: calc(1.5em + 0.75rem);
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>
