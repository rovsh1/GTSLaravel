<script setup lang="ts">
import { computed } from 'vue'

import { showCancelFeeDialog } from '~resources/views/booking/shared/lib/modals'
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

const handleUpdatePenalty = async () => {
  const { result: isConfirmed, cancelFeeAmount, toggleClose } = await showCancelFeeDialog({
    cancelFeeCurrencyLabel: order.value?.clientPrice.currency?.value || '--',
    cancelFeeAmount: order.value?.clientPenalty?.value,
  })
  if (isConfirmed) {
    toggleClose()
    orderStore.updatePenalty(cancelFeeAmount)
  }
}

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
      <a href="#" @click.prevent="handleUpdatePenalty">Изменить</a>
    </div>
  </div>
</template>
