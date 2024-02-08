<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { cancelPeriodOptions } from '~resources/views/booking/shared/lib/constants'

import { DateResponse } from '~api'
import { CancelPeriod, DatePeriod } from '~api/hotel/markup-settings'

import BaseDialog from '~components/BaseDialog.vue'
import DateRangePicker from '~components/DateRangePicker.vue'
import SelectComponent from '~components/SelectComponent.vue'

import { formatDateToAPIDate, parseAPIDateToJSDate } from '~helpers/date'

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

const isLoading = computed(() => Boolean(props.loading))

watch(localValue, (cancelPeriod) => {
  if (!cancelPeriod) {
    return
  }
  period.value = [parseAPIDateToJSDate(cancelPeriod.from), parseAPIDateToJSDate(cancelPeriod.to)]
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
  if (!cancelConditionForm.value?.reportValidity() || isLoading.value) {
    return
  }
  if (!period.value || !markupPercent.value || !markupType.value) {
    return
  }
  const payload: CancelPeriod = {
    from: formatDateToAPIDate(period.value[0]),
    to: formatDateToAPIDate(period.value[1]),
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
        v-if="opened"
        id="period"
        label="Период"
        required
        :lock-periods="cancelPeriods || [{
          from: minDate,
          to: maxDate,
        }] as DatePeriod[]"
        :editable-id="editableId"
        :min-date="minDate"
        :max-date="maxDate"
        :value="period"
        @input="(dates) => period = dates as [Date, Date]"
      />

      <div class="field-required">
        <label for="markup">Наценка</label>
        <input id="markup" v-model="markupPercent" type="number" class="form-control" required>
      </div>

      <div class="col-md-12">
        <SelectComponent
          v-if="opened"
          :options="cancelPeriodOptions"
          label="Процент от стоимости"
          :value="markupType"
          required
          @change="(value) => {
            markupType = value ? Number(value) : value
          }"
        />
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="isLoading" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" :disabled="isLoading" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
