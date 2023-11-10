<script setup lang="ts">
import { computed } from 'vue'

import { useToggle } from '@vueuse/core'

import { useCurrencyStore } from '~resources/store/currency'
import AmountBlock from '~resources/views/booking/shared/components/AmountBlock.vue'
import PriceModal from '~resources/views/booking/shared/components/PriceModal.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import { ProfitItem } from '~api/booking/models'
import { Currency } from '~api/models'

import { formatPrice } from '~lib/price'

const statusHistoryStore = useBookingStatusHistoryStore()

const lastHistoryItem = computed(() => statusHistoryStore.lastHistoryItem)
const [isNetPriceModalOpened, toggleNetPriceModal] = useToggle<boolean>(false)
const [isGrossPriceModalOpened, toggleGrossPriceModal] = useToggle<boolean>(false)
const [isNetPenaltyModalOpened, toggleNetPenaltyModal] = useToggle<boolean>(false)
const [isGrossPenaltyModalOpened, toggleGrossPenaltyModal] = useToggle<boolean>(false)

const bookingStore = useBookingStore()
const booking = computed(() => bookingStore.booking)

const profit = computed<ProfitItem | undefined>(() => bookingStore.booking?.prices.profit)

const { getCurrencyByCodeChar } = useCurrencyStore()
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.clientPrice.currency.value),
)

const netCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.supplierPrice.currency.value),
)

const getDisplayPriceValue = (type: 'client' | 'supplier') => {
  if (!booking.value) {
    return 0
  }
  if (type === 'client') {
    return booking.value?.prices.clientPrice.manualValue || booking.value?.prices.clientPrice.calculatedValue
  }

  return booking.value?.prices.supplierPrice.manualValue || booking.value?.prices.supplierPrice.calculatedValue
}

const handleSaveGrossManualPrice = async (value: number | undefined) => {
  toggleGrossPriceModal(false)
  await bookingStore.updatePrice({
    grossPrice: value,
  })
}

const handleSaveNetManualPrice = async (value: number | undefined) => {
  toggleNetPriceModal(false)
  await bookingStore.updatePrice({
    netPrice: value,
  })
}

const handleSaveBoPenalty = async (value: number | undefined) => {
  toggleGrossPenaltyModal(false)
  await bookingStore.updatePrice({
    grossPenalty: value,
  })
}

const handleSaveHoPenalty = async (value: number | undefined) => {
  toggleNetPenaltyModal(false)
  await bookingStore.updatePrice({
    netPenalty: value,
  })
}
</script>

<template>
  <div>
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

    <div v-if="lastHistoryItem && lastHistoryItem?.payload?.reason" class="mt-2 alert alert-warning" role="alert">
      {{ lastHistoryItem.payload.reason }}
    </div>
  </div>
</template>
