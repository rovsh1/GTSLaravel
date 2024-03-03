<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import BaseDialog from 'gts-components/Base/BaseDialog'

import { CarPriceItem } from '~api/booking/service'
import { Currency } from '~api/models'

const props = defineProps<{
  bookingId: number
  opened: MaybeRef<boolean>
  loading?: MaybeRef<boolean>
  carPrice?: CarPriceItem
  grossCurrency: Currency | undefined
}>()

interface SubmitPayload {
  grossPrice: number | undefined | null
}

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', payload: SubmitPayload): void
}>()

const isGrossPriceChanged = ref<boolean>(false)
const grossPrice = ref<number | null>()

const isLoading = computed(() => Boolean(props.loading))

const localGrossPrice = computed({
  get: () => (!isGrossPriceChanged.value ? props.carPrice?.manualValuePerCar : grossPrice.value),
  set: (value: number | undefined | null) => {
    isGrossPriceChanged.value = true
    grossPrice.value = value
  },
})

const submit = () => {
  if (isLoading.value) return
  emit('submit', { grossPrice: localGrossPrice.value })
}

</script>

<template>
  <BaseDialog :opened="opened as boolean" :loading="loading" @keydown.enter="submit" @close="$emit('close')">
    <template #title>Цена за автомобиль</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <label for="room-brutto-price">Цена за автомобиль (брутто) в {{ grossCurrency?.code_char }}</label>
        <input id="room-brutto-price" v-model.number="localGrossPrice" type="number" class="form-control">
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="isLoading" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" :disabled="isLoading" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
