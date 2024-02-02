<script setup lang="ts">
import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import { useCurrencyStore } from '~resources/store/currency'
import PriceModal from '~resources/views/booking/shared/components/PriceModal.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import { formatPrice } from '~lib/price'

const [isPenaltyModalOpened, togglePenaltyModal] = useToggle<boolean>(false)

const isUpdating = ref(false)

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const penaltyValue = computed(() => bookingStore.booking?.prices.supplierPrice.penaltyValue)

const penaltyCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.supplierPrice.currency.value),
)

const canEditPenalty = computed(() => bookingStore.availableActions?.canEditPenalty)

const handleSaveHoPenalty = async (value: number | null) => {
  isUpdating.value = true
  const isUpdated = await bookingStore.updatePrice({
    penalty: value,
  })
  if (isUpdated) {
    togglePenaltyModal(false)
  }
  isUpdating.value = false
}

</script>

<template>
  <div v-if="penaltyValue && penaltyCurrency" class="total-sum">
    Сумма штрафа:
    <strong>
      {{ formatPrice(penaltyValue, penaltyCurrency.code_char) }}
    </strong>
    <a v-if="canEditPenalty" style="margin-left: 5px;" href="#" @click.prevent="togglePenaltyModal(true)">Изменить</a>
  </div>
  <PriceModal
    v-if="canEditPenalty"
    header="Сумма штрафа"
    :label="`Сумма штрафа в ${penaltyCurrency?.code_char}`"
    :value="penaltyValue || undefined"
    :opened="isPenaltyModalOpened"
    :loading="isUpdating"
    @close="togglePenaltyModal(false)"
    @submit="handleSaveHoPenalty"
  />
</template>
