<script setup lang="ts">
import { computed } from 'vue'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import { formatPrice } from '~lib/price'

withDefaults(defineProps<{
  title: string
  currency: Currency | undefined
  amountTitle: string
  amountValue: number
  penaltyTitle: string
  penaltyValue: number | null | undefined
  needShowPenalty?: boolean
}>(), {
  penaltyValue: undefined,
  needShowPenalty: false,
})

defineEmits<{
  (event: 'clickChangePrice'): void
  (event: 'clickChangePenalty'): void
}>()

const bookingStore = useBookingStore()

const isEditable = computed(() => bookingStore.availableActions?.canEditFinancialCost || false)

</script>

<template>
  <div class="w-50 rounded pt-3 pb-3">
    <h6>{{ title }}</h6>
    <hr>
    <div v-if="currency">
      {{ amountTitle }}: {{ formatPrice(amountValue, currency.sign) }}
    </div>
    <a v-if="isEditable" href="#" @click.prevent="$emit('clickChangePrice')">Изменить</a>

    <div v-if="needShowPenalty && currency">
      <div>
        {{ penaltyTitle }}: {{ formatPrice((penaltyValue || 0), currency.sign) }}
      </div>
      <a v-if="isEditable" href="#" @click.prevent="$emit('clickChangePenalty')">Изменить</a>
    </div>
  </div>
</template>

<style scoped lang="scss">
hr {
  margin: 0.5rem 0 0.75rem;
}
</style>
