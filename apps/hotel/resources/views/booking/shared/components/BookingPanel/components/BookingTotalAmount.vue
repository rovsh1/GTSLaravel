<script setup lang="ts">
import { computed } from 'vue'

import { formatPrice } from 'gts-common/price'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import { useCurrencyStore } from '~stores/currency'

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const booking = computed(() => bookingStore.booking)
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

</script>

<template>
  <div v-if="booking && netCurrency" class="total-sum">
    Общая сумма:
    <strong>
      {{ formatPrice(getDisplayPriceValue('supplier'), netCurrency.code_char) }}
    </strong>
  </div>
</template>
