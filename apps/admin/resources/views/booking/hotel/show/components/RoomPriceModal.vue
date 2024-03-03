<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import BaseDialog from 'gts-components/Base/BaseDialog'

import { RoomBookingPrice } from '~api/booking/hotel/details'
import { Currency } from '~api/models'

const props = defineProps<{
  bookingId: number
  opened: MaybeRef<boolean>
  loading?: MaybeRef<boolean>
  roomPrice?: RoomBookingPrice
  netCurrency: Currency | undefined
  grossCurrency: Currency | undefined
}>()

interface SubmitPayload {
  grossPrice: number | undefined | null
  netPrice: number | undefined | null
}

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', payload: SubmitPayload): void
}>()

const isGrossPriceChanged = ref<boolean>(false)
const grossPrice = ref<number | null>()
const isNetPriceChanged = ref<boolean>(false)
const netPrice = ref<number | null>()

const isLoading = computed(() => Boolean(props.loading))

const localGrossPrice = computed({
  get: () => (!isGrossPriceChanged.value ? props.roomPrice?.grossDayValue : grossPrice.value),
  set: (value: number | undefined | null) => {
    isGrossPriceChanged.value = true
    grossPrice.value = value
  },
})

const localNetPrice = computed({
  get: () => (!isNetPriceChanged.value ? props.roomPrice?.netDayValue : netPrice.value),
  set: (value: number | undefined | null) => {
    isNetPriceChanged.value = true
    netPrice.value = value
  },
})

const submit = () => {
  if (isLoading.value) return
  emit('submit', { grossPrice: localGrossPrice.value, netPrice: localNetPrice.value })
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading"
    @keydown.enter="submit"
    @close="$emit('close')"
  >
    <template #title>Цена за номер</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <label for="room-brutto-price">Цена за номер (брутто) в {{ grossCurrency?.code_char }}</label>
        <input id="room-brutto-price" v-model.number="localGrossPrice" type="number" class="form-control">
      </div>

      <div class="col-md-12">
        <label for="room-netto-price">Цена за номер (нетто) в {{ netCurrency?.code_char }}</label>
        <input id="room-netto-price" v-model.number="localNetPrice" type="number" class="form-control">
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="isLoading" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" :disabled="isLoading" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
