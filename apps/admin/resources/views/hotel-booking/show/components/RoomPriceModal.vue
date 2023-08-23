<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { RoomBookingPrice } from '~api/booking/hotel/details'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  bookingId: number
  opened: MaybeRef<boolean>
  roomPrice?: RoomBookingPrice
}>()

interface SubmitPayload {
  boPrice: number | undefined | null
  hoPrice: number | undefined | null
}

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', payload: SubmitPayload): void
}>()

const isBoPriceChanged = ref<boolean>(false)
const boPrice = ref<number | null>()
const isHoPriceChanged = ref<boolean>(false)
const hoPrice = ref<number | null>()

const localBoPrice = computed({
  get: () => (!isBoPriceChanged.value ? props.roomPrice?.boDayValue : boPrice.value),
  set: (value: number | undefined | null) => {
    isBoPriceChanged.value = true
    boPrice.value = value
  },
})

const localHoPrice = computed({
  get: () => (!isHoPriceChanged.value ? props.roomPrice?.hoDayValue : hoPrice.value),
  set: (value: number | undefined | null) => {
    isHoPriceChanged.value = true
    hoPrice.value = value
  },
})

const submit = () => {
  emit('submit', { boPrice: localBoPrice.value, hoPrice: localHoPrice.value })
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    @close="$emit('close')"
    @keydown.enter="submit"
  >
    <template #title>Цена за номер</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <label for="room-brutto-price">Цена за номер (брутто) в UZS</label>
        <input id="room-brutto-price" v-model.number="localBoPrice" type="number" class="form-control">
      </div>

      <div class="col-md-12">
        <label for="room-netto-price">Цена за номер (нетто) в UZS</label>
        <input id="room-netto-price" v-model.number="localHoPrice" type="number" class="form-control">
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
