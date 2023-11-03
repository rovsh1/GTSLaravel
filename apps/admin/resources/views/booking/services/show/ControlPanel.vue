<script setup lang="ts">

import { computed } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useCurrencyStore } from '~resources/store/currency'
import { useBookingStore } from '~resources/views/booking/services/show/store/booking'
import { useBookingRequestStore } from '~resources/views/booking/services/show/store/request'
import { useBookingStatusHistoryStore } from '~resources/views/booking/services/show/store/status-history'
import AmountBlock from '~resources/views/booking/shared/components/AmountBlock.vue'
import ControlPanelSection from '~resources/views/booking/shared/components/ControlPanelSection.vue'
import PriceModal from '~resources/views/booking/shared/components/PriceModal.vue'
import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import StatusHistoryModal from '~resources/views/booking/shared/components/StatusHistoryModal.vue'
import StatusSelect from '~resources/views/booking/shared/components/StatusSelect.vue'
import { getCancelPeriodTypeName, getHumanRequestType } from '~resources/views/booking/shared/lib/constants'

import { CancelConditions } from '~api/booking/hotel/details'
import { BookingRequest } from '~api/booking/hotel/request'
import { BookingAvailableActionsResponse } from '~api/booking/hotel/status'
import { BookingStatusResponse, ProfitItem } from '~api/booking/models'
import { updateBookingPrice, useRecalculateBookingPriceAPI } from '~api/booking/service/price'
import { Currency } from '~api/models'

import { formatDate, formatDateTime } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'
import { formatPrice } from '~lib/price'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

const { bookingID } = requestInitialData('view-initial-data-service-booking', z.object({
  bookingID: z.number(),
}))

const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()
const { fetchBooking, fetchAvailableActions } = bookingStore
const booking = computed(() => bookingStore.booking)
const statuses = computed<BookingStatusResponse[] | null>(() => bookingStore.statuses)
const availableActions = computed<BookingAvailableActionsResponse | null>(() => bookingStore.availableActions)

const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.clientPrice.currency.value),
)

const netCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.supplierPrice.currency.value),
)

const profit = computed<ProfitItem | undefined>(() => bookingStore.booking?.prices.profit)

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore

const requestStore = useBookingRequestStore()
const bookingRequests = computed<BookingRequest[] | null>(() => requestStore.requests)

const cancelConditions = computed<CancelConditions | null>(() => bookingStore.booking?.cancelConditions || null)

const isGuestsFilled = computed<boolean>(() => true)

const { isFetching: isRecalculateBookingPrice, execute: recalculateBookingPrice } = useRecalculateBookingPriceAPI({ bookingID })

const isRequestableStatus = computed<boolean>(() => availableActions.value?.isRequestable || false)
const isAvailableActionsFetching = computed<boolean>(() => bookingStore.isAvailableActionsFetching)
const isStatusUpdateFetching = computed(() => bookingStore.isStatusUpdateFetching)
const isRequestFetching = computed(() => requestStore.requestSendIsFetching)

const canSendCancellationRequest = computed<boolean>(() => availableActions.value?.canSendCancellationRequest || false)
const canSendBookingRequest = computed<boolean>(() => availableActions.value?.canSendBookingRequest || false)
const canSendChangeRequest = computed<boolean>(() => availableActions.value?.canSendChangeRequest || false)

const [isNetPriceModalOpened, toggleNetPriceModal] = useToggle<boolean>(false)
const [isGrossPriceModalOpened, toggleGrossPriceModal] = useToggle<boolean>(false)
const [isNetPenaltyModalOpened, toggleNetPenaltyModal] = useToggle<boolean>(false)
const [isGrossPenaltyModalOpened, toggleGrossPenaltyModal] = useToggle<boolean>(false)

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

const handleSaveGrossManualPrice = async (value: number | undefined) => {
  toggleGrossPriceModal(false)
  await updateBookingPrice({
    bookingID,
    grossPrice: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveNetManualPrice = async (value: number | undefined) => {
  toggleNetPriceModal(false)
  await updateBookingPrice({
    bookingID,
    netPrice: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveBoPenalty = async (value: number | undefined) => {
  toggleGrossPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    grossPenalty: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveHoPenalty = async (value: number | undefined) => {
  toggleNetPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    netPenalty: value,
  })
  bookingStore.fetchBooking()
}

const handleRecalculatePrice = async () => {
  await recalculateBookingPrice()
  fetchBooking()
}

const getDisplayPriceValue = (type: 'client' | 'supplier') => {
  if (!booking.value) {
    return 0
  }
  if (type === 'client') {
    return booking.value?.prices.clientPrice.manualValue || booking.value?.prices.clientPrice.calculatedValue
  }

  return booking.value?.prices.supplierPrice.manualValue || booking.value?.prices.supplierPrice.calculatedValue
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

  <PriceModal
    header="Общая сумма (нетто)"
    :label="`Общая сумма (нетто) в ${netCurrency?.code_char}`"
    :value="booking?.prices.supplierPrice.manualValue || undefined"
    :opened="isNetPriceModalOpened"
    @close="toggleNetPriceModal(false)"
    @submit="handleSaveNetManualPrice"
  />

  <PriceModal
    header="Общая сумма (брутто)"
    :label="`Общая сумма (брутто) ${grossCurrency?.code_char}`"
    :value="booking?.prices.clientPrice.manualValue || undefined"
    :opened="isGrossPriceModalOpened"
    @close="toggleGrossPriceModal(false)"
    @submit="handleSaveGrossManualPrice"
  />

  <PriceModal
    header="Сумма штрафа для клиента"
    :label="`Сумма штрафа для клиента в ${grossCurrency?.code_char}`"
    :value="booking?.prices.clientPrice.penaltyValue || undefined"
    :opened="isGrossPenaltyModalOpened"
    @close="toggleGrossPenaltyModal(false)"
    @submit="handleSaveBoPenalty"
  />

  <PriceModal
    header="Сумма штрафа от поставщика"
    :label="`Сумма штрафа от поставщика в ${netCurrency?.code_char}`"
    :value="booking?.prices.supplierPrice.penaltyValue || undefined"
    :opened="isNetPenaltyModalOpened"
    @close="toggleNetPenaltyModal(false)"
    @submit="handleSaveHoPenalty"
  />

  <div class="d-flex flex-wrap flex-grow-1 gap-2 align-items-center">
    <StatusSelect
      v-if="booking && statuses"
      v-model="booking.status"
      :statuses="statuses"
      :available-statuses="availableActions?.statuses || null"
      :is-loading="isStatusUpdateFetching || isAvailableActionsFetching"
      @change="handleStatusChange"
    />
    <a href="#" class="btn-log" @click.prevent="toggleHistoryModal()">История изменений</a>
    <div v-if="booking && grossCurrency" class="float-end total-sum">
      Общая сумма:
      <strong>
        {{ formatPrice(getDisplayPriceValue('client'), grossCurrency.sign) }}
      </strong>
      <span v-if="booking.prices.clientPrice.isManual" class="text-muted"> (выставлена вручную)</span>
    </div>
  </div>

  <ControlPanelSection title="Финансовая стоимость брони" class="mt-4">
    <OverlayLoading v-if="isRecalculateBookingPrice" />
    <template #actions>
      <BootstrapButton
        label="Пересчитать"
        size="small"
        severity="secondary"
        variant="outline"
        :disabled="isRecalculateBookingPrice"
        @click="handleRecalculatePrice"
      />
    </template>
    <div class="d-flex flex-row gap-3">
      <AmountBlock
        v-if="booking"
        title="Приход"
        :currency="grossCurrency"
        amount-title="Общая сумма (брутто)"
        :amount-value="getDisplayPriceValue('client')"
        penalty-title="Сумма штрафа для клиента"
        :penalty-value="booking.prices.clientPrice.penaltyValue"
        :need-show-penalty="(booking?.prices.supplierPrice.penaltyValue || 0) > 0"
        @click-change-price="toggleGrossPriceModal(true)"
        @click-change-penalty="toggleGrossPenaltyModal(true)"
      />

      <AmountBlock
        v-if="booking"
        title="Расход"
        :currency="netCurrency"
        amount-title="Общая сумма (нетто)"
        :amount-value="getDisplayPriceValue('supplier')"
        penalty-title="Сумма штрафа от поставщика"
        :penalty-value="booking.prices.supplierPrice.penaltyValue"
        :need-show-penalty="(booking?.prices.supplierPrice.penaltyValue || 0) > 0"
        @click-change-price="toggleNetPriceModal(true)"
        @click-change-penalty="toggleNetPenaltyModal(true)"
      />
    </div>

    <div v-if="booking && grossCurrency && netCurrency" class="mt-2">
      Прибыль = {{ formatPrice(profit?.clientValue, grossCurrency.sign) }} - {{
        formatPrice(profit?.supplierValue, grossCurrency.sign) }} =
      {{
        formatPrice(profit?.profitValue, grossCurrency.sign)
      }}
    </div>
  </ControlPanelSection>

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
      <div v-if="isGuestsFilled">
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
        text="Для отправки запроса необходимо заполнить информацию о гостях"
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
