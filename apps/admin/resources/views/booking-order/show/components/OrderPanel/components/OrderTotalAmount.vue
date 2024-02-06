<script setup lang="ts">
import { computed } from 'vue'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { Currency } from '~api/models'
import { Order } from '~api/order'

import { useCurrencyStore } from '~stores/currency'

import { formatPrice } from '~helpers/price'

const orderStore = useOrderStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const order = computed<Order | null>(() => orderStore.order)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(orderStore.order?.clientPrice.currency?.value),
)

</script>

<template>
  <div v-if="order && grossCurrency" class="float-end total-sum">
    Общая сумма:
    <strong>
      {{ formatPrice(order.clientPrice.value, grossCurrency.code_char) }}
    </strong>
    <span v-if="false" class="text-muted"> (выставлена вручную)</span>

    <div v-if="order.clientPenalty">
      Сумма штрафа:
      <strong>
        {{ formatPrice(order.clientPenalty.value, grossCurrency.code_char) }}
      </strong>
    </div>
  </div>
</template>
