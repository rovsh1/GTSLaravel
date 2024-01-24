<script setup lang="ts">
import { computed } from 'vue'

import { useToggle } from '@vueuse/core'

import { useCurrencyStore } from '~resources/store/currency'
import PriceModal from '~resources/views/booking/shared/components/PriceModal.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import { formatPrice } from '~lib/price'

const [isPenaltyModalOpened, togglePenaltyModal] = useToggle<boolean>(false)

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const penaltyValue = computed(() => bookingStore.booking?.prices.supplierPrice.penaltyValue)

const penaltyCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.supplierPrice.currency.value),
)

const isEditable = computed(() => bookingStore.availableActions?.isEditable)

const handleSaveHoPenalty = async (value: number | undefined | null) => {
  togglePenaltyModal(false)
  await bookingStore.updatePrice({
    netPenalty: value,
  })
}

</script>

<template>
  <div v-if="penaltyValue && penaltyCurrency" class="total-sum">
    Сумма штрафа:
    <strong>
      {{ formatPrice(penaltyValue, penaltyCurrency.sign) }}
    </strong>
    <a v-if="isEditable" style="margin-left: 5px;" href="#" @click.prevent="togglePenaltyModal(true)">Изменить</a>
  </div>
  <PriceModal
    header="Сумма штрафа"
    :label="`Сумма штрафа в ${penaltyCurrency?.code_char}`"
    :value="penaltyValue || undefined"
    :opened="isPenaltyModalOpened"
    @close="togglePenaltyModal(false)"
    @submit="handleSaveHoPenalty"
  />
</template>
