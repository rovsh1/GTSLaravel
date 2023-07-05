<script setup lang="ts">

import { ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import BaseDialog from '~components/BaseDialog.vue'

defineProps<{
  bookingId: number
  opened: MaybeRef<boolean>
  roomBookingId: MaybeRef<number>
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

const boPrice = ref<number>()
const hoPrice = ref<number>()

const submit = () => {
  emit('submit')
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    @close="$emit('close')"
    @keyup.enter="submit"
  >
    <template #title>Цена за номер</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <label for="room-brutto-price">Цена за номер (брутто) в UZS</label>
        <input id="room-brutto-price" v-model.number="boPrice" type="number" class="form-control">
      </div>

      <div class="col-md-12">
        <label for="room-netto-price">Цена за номер (нетто) в UZS</label>
        <input id="room-netto-price" v-model.number="hoPrice" type="number" class="form-control">
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
