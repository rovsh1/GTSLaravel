<script setup lang="ts">
import { computed } from 'vue'

import { useCurrencyStore } from '~resources/store/currency'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { Currency } from '~api/models'

import { formatPrice } from '~lib/price'

const orderStore = useOrderStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const order = computed(() => orderStore.order)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(orderStore.order?.currency?.value),
)

const getDisplayPriceValue = () =>
  // @todo итого цена заказа
  0

</script>

<template>
  <div v-if="order && grossCurrency" class="float-end total-sum">
    Общая сумма:
    <strong>
      {{ formatPrice(getDisplayPriceValue(), grossCurrency.sign) }}
    </strong>
    <span v-if="false" class="text-muted"> (выставлена вручную)</span>
  </div>
</template>
