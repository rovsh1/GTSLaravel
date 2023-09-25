<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useCurrencyStore } from '~resources/store/currency'
import RequestBlock from '~resources/views/booking/components/RequestBlock.vue'
import AmountBlock from '~resources/views/hotel-booking/show/components/AmountBlock.vue'
import ControlPanelSection from '~resources/views/hotel-booking/show/components/ControlPanelSection.vue'
import PriceModal from '~resources/views/hotel-booking/show/components/PriceModal.vue'
import StatusHistoryModal from '~resources/views/hotel-booking/show/components/StatusHistoryModal.vue'
import { useExternalNumber } from '~resources/views/hotel-booking/show/composables/external-number'
import { externalNumberTypeOptions, getCancelPeriodTypeName, getHumanRequestType } from '~resources/views/hotel-booking/show/lib/constants'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'
import { useBookingRequestStore } from '~resources/views/hotel-booking/show/store/request'
import { useBookingStatusHistoryStore } from '~resources/views/hotel-booking/show/store/status-history'

// import { useBookingVoucherStore } from '~resources/views/hotel-booking/show/store/voucher'
import { Booking } from '~api/booking/hotel'
import { CancelConditions, ExternalNumberType, ExternalNumberTypeEnum } from '~api/booking/hotel/details'
import { updateBookingPrice } from '~api/booking/hotel/price'
import { BookingRequest } from '~api/booking/hotel/request'
import { BookingAvailableActionsResponse } from '~api/booking/hotel/status'
import { BookingStatusResponse } from '~api/booking/models'
import { Currency } from '~api/models'

import { formatDate, formatDateTime } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'
import { formatPrice } from '~lib/price'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

import StatusSelect from './components/StatusSelect.vue'

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const { getCurrencyByCodeChar } = useCurrencyStore()
const bookingStore = useBookingStore()
const { fetchBooking, fetchAvailableActions } = bookingStore
const requestStore = useBookingRequestStore()
const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore
// const voucherStore = useBookingVoucherStore()
// const vouchers = computed(() => voucherStore.vouchers)
const {
  externalNumberType,
  externalNumber,
  isExternalNumberValid,
  isUpdateExternalNumberFetching,
  isExternalNumberChanged,
  // validateExternalNumber,
  updateExternalNumber,
  hideValidation,
} = useExternalNumber(bookingID)

const booking = computed<Booking | null>(() => bookingStore.booking)
const cancelConditions = computed<CancelConditions | null>(() => bookingStore.booking?.cancelConditions || null)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.price.grossPrice.currency.value),
)
const netCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.price.netPrice.currency.value),
)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)

// flags
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isRequestableStatus = computed<boolean>(() => availableActions.value?.isRequestable || false)
const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)
const isRequestFetching = computed(() => requestStore.requestSendIsFetching)
// const isVoucherFetching = computed(() => voucherStore.voucherSendIsFetching)
const isNeedShowExternalNumber = computed<boolean>(
  () => Number(externalNumberType.value) === ExternalNumberTypeEnum.HotelBookingNumber,
)
const isExternalNumberInvalid = computed(() => !isExternalNumberValid.value)

// access
// const canSendClientVoucher = computed<boolean>(() => {
//   if (!availableActions.value?.canSendVoucher) {
//     return false
//   }
//
//   return (vouchers.value?.length || 0) === 0
// })
const canSendCancellationRequest = computed<boolean>(() => availableActions.value?.canSendCancellationRequest || false)
const canSendBookingRequest = computed<boolean>(() => availableActions.value?.canSendBookingRequest || false)
const canSendChangeRequest = computed<boolean>(() => availableActions.value?.canSendChangeRequest || false)
const canEditExternalNumber = computed<boolean>(() => availableActions.value?.canEditExternalNumber || false)
const isRoomsAndGuestsFilled = computed<boolean>(() => !bookingStore.isEmptyGuests && !bookingStore.isEmptyRooms)
const bookingRequests = computed<BookingRequest[] | null>(() => {
  const requests = requestStore.requests?.reduce((rv: Record<number, BookingRequest>, x: BookingRequest) => {
    // eslint-disable-next-line no-param-reassign
    rv[x.type] = x
    return rv
  }, {})
  return Object.values(requests || {})
})
const lastHistoryItem = computed(() => statusHistoryStore.lastHistoryItem)
const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)
const [isNetPriceModalOpened, toggleNetPriceModal] = useToggle<boolean>(false)
const [isGrossPriceModalOpened, toggleGrossPriceModal] = useToggle<boolean>(false)
const [isNetPenaltyModalOpened, toggleNetPenaltyModal] = useToggle<boolean>(false)
const [isGrossPenaltyModalOpened, toggleGrossPenaltyModal] = useToggle<boolean>(false)

const handleStatusChange = async (value: number): Promise<void> => {
  hideValidation()
  await bookingStore.changeStatus(value)
  await fetchStatusHistory()
}

const handleRequestSend = async () => {
  hideValidation()
  await requestStore.sendRequest()
  await Promise.all([
    fetchAvailableActions(),
    fetchBooking(),
    fetchStatusHistory(),
  ])
}

const handleUpdateExternalNumber = async () => {
  const isSuccess = await updateExternalNumber()
  if (isSuccess) {
    isExternalNumberChanged.value = false
  }
}

// const handleVoucherSend = async () => {
//   if (!validateExternalNumber()) {
//     return
//   }
//   if (isExternalNumberChanged.value) {
//     await handleUpdateExternalNumber()
//   }
//   await voucherStore.sendVoucher()
// }

const handleSaveGrossManualPrice = async (value: number | undefined) => {
  toggleGrossPriceModal(false)
  await updateBookingPrice({
    bookingID,
    grossPrice: value,
  })
  fetchBooking()
  fetchAvailableActions()
}

const handleSaveNetManualPrice = async (value: number | undefined) => {
  toggleNetPriceModal(false)
  await updateBookingPrice({
    bookingID,
    netPrice: value,
  })
  fetchBooking()
  fetchAvailableActions()
}

const handleSaveGrossPenalty = async (value: number | undefined) => {
  toggleGrossPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    grossPenalty: value,
  })
  fetchBooking()
}

const handleSaveNetPenalty = async (value: number | undefined) => {
  toggleNetPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    netPenalty: value,
  })
  fetchBooking()
}

const getDisplayPriceValue = (type: 'gross' | 'net') => {
  if (!booking.value) {
    return 0
  }
  if (type === 'gross') {
    return booking.value?.price.grossPrice.manualValue || booking.value?.price.grossPrice.calculatedValue
  }

  return booking.value?.price.netPrice.manualValue || booking.value?.price.netPrice.calculatedValue
}

onMounted(() => {
  fetchAvailableActions()
})

</script>

<template>
  <StatusHistoryModal
    :opened="isHistoryModalOpened"
    :is-fetching="statusHistoryStore.isFetching"
    :status-history-events="statusHistoryStore.statusHistoryEvents"
    @close="toggleHistoryModal(false)"
    @refresh="fetchStatusHistory"
  />

  <PriceModal
    header="Общая сумма (нетто)"
    :label="`Общая сумма (нетто) в ${netCurrency?.code_char}`"
    :value="booking?.price.netPrice.manualValue || undefined"
    :opened="isNetPriceModalOpened"
    @close="toggleNetPriceModal(false)"
    @submit="handleSaveNetManualPrice"
  />

  <PriceModal
    header="Общая сумма (брутто)"
    :label="`Общая сумма (брутто) ${grossCurrency?.code_char}`"
    :value="booking?.price.grossPrice.manualValue || undefined"
    :opened="isGrossPriceModalOpened"
    @close="toggleGrossPriceModal(false)"
    @submit="handleSaveGrossManualPrice"
  />

  <PriceModal
    header="Сумма штрафа для клиента"
    :label="`Сумма штрафа для клиента в ${grossCurrency?.code_char}`"
    :value="booking?.price.grossPrice.penaltyValue || undefined"
    :opened="isGrossPenaltyModalOpened"
    @close="toggleGrossPenaltyModal(false)"
    @submit="handleSaveGrossPenalty"
  />

  <PriceModal
    header="Сумма штрафа от гостиницы"
    :label="`Сумма штрафа от гостиницы в ${netCurrency?.code_char}`"
    :value="booking?.price.netPrice.penaltyValue || undefined"
    :opened="isNetPenaltyModalOpened"
    @close="toggleNetPenaltyModal(false)"
    @submit="handleSaveNetPenalty"
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
    <div v-if="booking && grossCurrency" class="float-end">
      Общая сумма:
      <strong>
        {{ formatPrice(getDisplayPriceValue('gross'), grossCurrency.sign) }}
      </strong>
      <span v-if="booking.price.grossPrice.isManual" class="text-muted">(выставлена вручную)</span>
    </div>
  </div>

  <ControlPanelSection title="Тип номера подтверждения бронирования" class="mt-4">
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
  </ControlPanelSection>

  <ControlPanelSection title="Финансовая стоимость брони" class="mt-4">
    <div class="d-flex flex-row gap-3">
      <AmountBlock
        v-if="booking"
        title="Приход"
        :currency="grossCurrency"
        amount-title="Общая сумма (брутто)"
        :amount-value="getDisplayPriceValue('gross')"
        penalty-title="Сумма штрафа для клиента"
        :penalty-value="booking.price.grossPenalty"
        :need-show-penalty="(booking?.price.netPenalty || 0) > 0"
        @click-change-price="toggleGrossPriceModal(true)"
        @click-change-penalty="toggleGrossPenaltyModal(true)"
      />

      <AmountBlock
        v-if="booking"
        title="Расход"
        :currency="netCurrency"
        amount-title="Общая сумма (нетто)"
        :amount-value="getDisplayPriceValue('net')"
        penalty-title="Сумма штрафа от гостиницы"
        :penalty-value="booking.price.netPenalty"
        :need-show-penalty="(booking?.price.netPenalty || 0) > 0"
        @click-change-price="toggleNetPriceModal(true)"
        @click-change-penalty="toggleNetPenaltyModal(true)"
      />
    </div>

    <div v-if="booking && grossCurrency && netCurrency" class="mt-2">
      Прибыль = {{ formatPrice(getDisplayPriceValue('gross'), grossCurrency.sign) }} - {{ formatPrice(getDisplayPriceValue('net'), netCurrency.sign) }} =
      {{
        formatPrice((getDisplayPriceValue('gross') - getDisplayPriceValue('net')), grossCurrency.sign)
      }}
    </div>

    <div v-if="lastHistoryItem && lastHistoryItem?.payload?.reason" class="mt-2 alert alert-warning" role="alert">
      {{ lastHistoryItem.payload.reason }}
    </div>
  </ControlPanelSection>

  <ControlPanelSection title="Запросы в гостиницу" class="mt-4">
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
        <a href="#" class="btn-download" @click.prevent="requestStore.downloadDocument(bookingRequest.id)">Скачать</a>
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

  <!--  <ControlPanelSection title="Файлы, отправленные клиенту" class="mt-4">-->
  <!--    <div class="reservation-requests mb-2">-->
  <!--      <div-->
  <!--        v-for="bookingVoucher in vouchers"-->
  <!--        :key="bookingVoucher.id"-->
  <!--        class="d-flex flex-row justify-content-between w-100 py-1"-->
  <!--      >-->
  <!--        <div>-->
  <!--          {{ true ? 'Ваучер' : 'Общий ваучер' }}-->
  <!--          <span class="date align-left ml-1">
  от {{ formatDateTime(bookingVoucher.dateCreate) }}</span>-->
  <!--        </div>-->
  <!-- <a
  href="#"
  class="btn-download"
  @click.prevent="voucherStore.downloadDocument(bookingVoucher.id)"
  >
  Скачать
  </a>-->
  <!--      </div>-->
  <!--    </div>-->

  <!--    @todo будет перенесено в order-->
  <!--    <RequestBlock-->
  <!--      v-if="canSendClientVoucher"-->
  <!--      variant="success"-->
  <!--      text="При необходимости клиенту можно отправить ваучер"-->
  <!--      button-text="Отправить ваучер"-->
  <!--      :loading="isVoucherFetching"-->
  <!--      @click="handleVoucherSend"-->
  <!--    />-->
  <!--  </ControlPanelSection>-->

  <ControlPanelSection title="Условия отмены" class="mt-4">
    <table class="table-params">
      <tbody>
        <tr>
          <th>Отмена без штрафа</th>
          <td>до {{ cancelConditions?.cancelNoFeeDate ? formatDate(cancelConditions?.cancelNoFeeDate) : '-' }}</td>
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

<style scoped lang="scss">
.ml-2 {
  margin-left: 0.5rem;
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
  padding-right: calc(1.5em + 0.75rem);
  border-color: var(--bs-form-invalid-border-color);
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
  background-repeat: no-repeat;
}
</style>
