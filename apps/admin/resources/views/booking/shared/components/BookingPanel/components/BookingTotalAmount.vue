<script setup lang="ts">
import { computed } from 'vue'

import { useCurrencyStore } from '~resources/store/currency'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import { formatPrice } from '~lib/price'

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const booking = computed(() => bookingStore.booking)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.clientPrice.currency.value),
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

</script>

<template>
  <div v-if="booking && grossCurrency" class="float-end total-sum">
    Общая сумма:
    <strong>
      {{ formatPrice(getDisplayPriceValue('client'), grossCurrency.sign) }}
    </strong>
    <span v-if="booking.prices.clientPrice.isManual" class="text-muted"> (выставлена вручную)</span>
  </div>
</template>
