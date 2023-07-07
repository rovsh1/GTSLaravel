<script setup lang="ts">

import { ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { cancelPeriodOptions } from '~resources/views/hotel-booking/show/composables/constants'

import { DailyMarkup } from '~api/hotel/markup-settings'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

withDefaults(defineProps<{
  opened: MaybeRef<boolean>
  title: string
  loading?: MaybeRef<boolean>
}>(), {
  loading: false,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', value: DailyMarkup): void
}>()

const ignoreElements = ['.litepicker']

const daysCount = ref<number>()
const markupPercent = ref<number>()
const markupType = ref<number>()

const clearForm = () => {
  daysCount.value = undefined
  markupPercent.value = undefined
  markupType.value = undefined
}

const closeModal = () => {
  clearForm()
  emit('close')
}

const cancelConditionForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!cancelConditionForm.value?.reportValidity()) {
    return
  }
  if (!daysCount.value || !markupPercent.value || !markupType.value) {
    return
  }
  const payload: DailyMarkup = {
    percent: markupPercent.value,
    cancelPeriodType: markupType.value,
    daysCount: daysCount.value,
  }

  emit('submit', payload)
  clearForm()
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading as boolean"
    :click-outside-ignore="ignoreElements"
    @close="closeModal"
    @keyup.enter="onModalSubmit"
  >
    <template #title>{{ title }}</template>

    <form ref="cancelConditionForm" class="row g-3">
      <div class="field-required">
        <label for="markup">Кол-во дней</label>
        <input id="markup" v-model="daysCount" type="number" class="form-control" required>
      </div>

      <div class="field-required">
        <label for="markup">Наценка</label>
        <input id="markup" v-model="markupPercent" type="number" class="form-control" required>
      </div>

      <div class="col-md-12">
        <BootstrapSelectBase
          id="type"
          label="Процент от стоимости"
          :options="cancelPeriodOptions"
          :value="markupType"
          required
          @input="value => markupType = Number(value)"
        />
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
