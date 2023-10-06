<script setup lang="ts">

import { computed, nextTick, Ref, ref, watch, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { isDataValid, validateForm } from '~resources/composables/form'
import {
  mapEntitiesToSelectOptions,
} from '~resources/views/booking/lib/constants'
import { TransferFormData } from '~resources/views/booking/lib/data-types'
import CarsTable from '~resources/views/transfer-booking/show/components/CarsTable.vue'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
  formData: Partial<TransferFormData>
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit'): void
}>()

const { bookingID } = requestInitialData(
  'view-initial-data-transfer-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const residentTypeOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'Резидент' },
  { id: 0, name: 'Не резидент' },
])

const setFormData = () => ({
  bookingID,
  ...props.formData,
})

const formData = ref<TransferFormData>(setFormData())

watchEffect(() => {
  formData.value = setFormData()
})

const validateRoomForm = computed(() => (isDataValid(null, formData.value.id)))

const isFetching = ref<boolean>(false)
const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!validateForm<TransferFormData>(modalForm as Ref<HTMLFormElement>, formData)) {
    return
  }
  isFetching.value = true

  isFetching.value = false
  emit('submit')
}

const closeModal = () => {
  emit('close')
}

const resetForm = () => {
  emit('clear')
  formData.value.id = undefined
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

watch(() => props.opened, async (opened) => {
  if (opened) {
    // await fetchAvailableRooms()
    // setPreparedRooms()
  }
})

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="closeModal"
    @keydown.enter="onModalSubmit"
  >
    <template #title>Редактирование информации о брони</template>
    <form ref="modalForm" class="row g-3">
      <div class="col-md-6">
        <div class="field-required">
          <label for="legal-name">Номер поезда</label>
          <input
            id="legal-name"
            class="form-control"
            required
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="field-required">
          <label for="legal-name">Дата прибытия</label>
          <input
            id="legal-name"
            class="form-control"
            required
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="field-required">
          <label for="legal-name">Время прибытия</label>
          <input
            id="legal-name"
            class="form-control"
            required
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="field-required">
          <label for="legal-name">Город прибытия</label>
          <input
            id="legal-name"
            class="form-control"
            required
          >
        </div>
      </div>
      <div class="col-md-12">
        <div class="field-required">
          <label for="legal-name">Табличка для встречи</label>
          <input
            id="legal-name"
            class="form-control"
            required
          >
        </div>
      </div>
      <div class="col-md-12">
        <CarsTable />
      </div>
    </form>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        @click="onModalSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
