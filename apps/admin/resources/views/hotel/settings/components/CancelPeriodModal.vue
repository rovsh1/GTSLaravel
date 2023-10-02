<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { cancelPeriodOptions } from '~resources/views/booking/lib/constants'

import { DateResponse } from '~api'
import { CancelPeriod } from '~api/hotel/markup-settings'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import DateRangePicker from '~components/DateRangePicker.vue'

const props = withDefaults(defineProps<{
  value?: CancelPeriod
  opened: MaybeRef<boolean>
  title: string
  loading?: MaybeRef<boolean>
  cancelPeriods?: CancelPeriod[]
  editableId?: number
  minDate?: DateResponse
  maxDate?: DateResponse
}>(), {
  loading: false,
  value: undefined,
  cancelPeriods: undefined,
  editableId: undefined,
  minDate: undefined,
  maxDate: undefined,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', value: CancelPeriod): void
}>()

const ignoreElements = ['.litepicker']

const localValue = computed(() => props.value)
const period = ref<[Date, Date]>()
const markupPercent = ref<number>()
const markupType = ref<number>()

watch(localValue, (cancelPeriod) => {
  if (!cancelPeriod) {
    return
  }
  period.value = [new Date(cancelPeriod.from), new Date(cancelPeriod.to)]
  markupPercent.value = cancelPeriod.noCheckInMarkup.percent
  markupType.value = cancelPeriod.noCheckInMarkup.cancelPeriodType
})

const clearForm = () => {
  period.value = undefined
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
  if (!period.value || !markupPercent.value || !markupType.value) {
    return
  }
  const payload: CancelPeriod = {
    from: period.value[0].toISOString(),
    to: period.value[1].toISOString(),
    noCheckInMarkup: {
      percent: markupPercent.value,
      cancelPeriodType: markupType.value,
    },
    dailyMarkups: [],
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
    @keydown.enter="onModalSubmit"
  >
    <template #title>{{ title }}</template>

    <form ref="cancelConditionForm" class="row g-3">
      <DateRangePicker
        id="period"
        label="Период"
        required
        :lock-periods="cancelPeriods"
        :editable-id="editableId"
        :min-date="minDate"
        :max-date="maxDate"
        :value="period"
        @input="(dates) => period = dates"
      />

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
          @input="val => markupType = Number(val)"
        />
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
