<script setup lang="ts">
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue'

import { onClickOutside } from '@vueuse/core'
import { Litepicker } from 'litepicker'
import { DateTime, Interval } from 'luxon'

import { DateResponse } from '~api'
import { DatePeriod } from '~api/hotel/markup-settings'

import { compareJSDate, formatPeriod, parseAPIDate } from '~lib/date'
import { useDateRangePicker, useSingleDatePicker } from '~lib/date-picker/date-picker'

const props = withDefaults(defineProps<{
  id: string
  label?: string
  isError?: boolean
  errorText?: string
  labelOutline?: boolean
  required?: boolean
  disabled?: boolean
  value?: [Date, Date] | Date
  lockPeriods?: DatePeriod[]
  editableId?: number
  minDate?: DateResponse
  maxDate?: DateResponse
  singleMode?: boolean
  setInputFocus?: boolean
}>(), {
  required: false,
  label: undefined,
  disabled: undefined,
  value: undefined,
  lockPeriods: undefined,
  editableId: undefined,
  minDate: undefined,
  maxDate: undefined,
  labelOutline: true,
  isError: false,
  errorText: '',
  singleMode: false,
  setInputFocus: false,
})

const emit = defineEmits<{
  (event: 'input', value: [Date, Date] | Date): void
  (event: 'pressEnter'): void
  (event: 'pressEsc'): void
  (event: 'clickOutside'): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)
const localValue = computed(() => props.value)
const displayValue = computed(() => {
  if (props.value) {
    if (!Array.isArray(props.value)) return ''
    const period = {
      date_start: props.value[0].toISOString(),
      date_end: props.value[1].toISOString(),
    }
    return formatPeriod(period)
  }

  return ''
})

const lockPeriods = computed(() => props.lockPeriods)
const editableId = computed(() => props.editableId)
const minDateTime = computed(() => props.minDate && parseAPIDate(props.minDate).startOf('day'))
const maxDateTime = computed(() => props.maxDate && parseAPIDate(props.maxDate).endOf('day'))

const lockDaysFilter = (inputDate: any) => {
  if (inputDate === null) {
    return false
  }
  const inputDateTime = DateTime.fromJSDate(inputDate.toJSDate()).startOf('day')
  if (minDateTime.value && inputDateTime < minDateTime.value) {
    return true
  }
  if (maxDateTime.value && inputDateTime > maxDateTime.value) {
    return true
  }
  return lockPeriods.value?.find((period, index): boolean => {
    const isSamePeriod = editableId.value === index
    const { from, to } = period
    const start = parseAPIDate(from).startOf('day')
    const end = parseAPIDate(to).endOf('day')

    return !isSamePeriod && Interval.fromDateTimes(start, end).contains(inputDateTime)
  }) !== undefined
}

onClickOutside(inputRef, (event: MouseEvent) => {
  const dropDownLitepickerElement = document.querySelector('.litepicker')
  if (!dropDownLitepickerElement?.contains(event.target as Node)) {
    emit('clickOutside')
  }
})

let picker: Litepicker

onMounted(() => {
  nextTick(() => {
    const periodInput = document.getElementById(props.id) as HTMLInputElement
    if (props.singleMode) {
      picker = useSingleDatePicker(periodInput, { lockDaysFilter, singleMode: props.singleMode })
    } else {
      picker = useDateRangePicker(periodInput, { lockDaysFilter, singleMode: props.singleMode })
    }
    picker.on('before:show', () => {
      if (localValue.value) {
        if (Array.isArray(localValue.value)) {
          picker.setDateRange(localValue.value[0], localValue.value[1])
        } else {
          picker.setDate(localValue.value)
        }
      } else {
        picker.clearSelection()
      }
    })
    picker.on('selected', (date1: any, date2: any) => {
      if (props.singleMode) {
        if (!Array.isArray(localValue.value) && localValue.value && !compareJSDate(localValue.value, date1.dateInstance)) {
          emit('input', date1.dateInstance)
        } else if (!localValue.value) {
          emit('input', date1.dateInstance)
        }
      } else {
        emit('input', [date1.dateInstance, date2.dateInstance])
      }
    })
    if (props.setInputFocus) {
      periodInput.focus()
    }
  })
})

onUnmounted(() => {
  if (picker) {
    picker.destroy()
  }
})
</script>

<template>
  <div :class="{ 'field-required': required }" style="position: relative;">
    <label v-if="label" :class="{ 'label-inline': !labelOutline }" :for="id">{{ label }}</label>
    <input
      :id="id"
      ref="inputRef"
      class="form-control"
      :required="required"
      :disabled="disabled"
      :value="displayValue"
      @keydown.esc="emit('pressEsc')"
      @keydown.enter="emit('pressEnter')"
    >
    <div v-if="isError" class="invalid-feedback">{{ errorText }}</div>
  </div>
</template>

<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.compactSelect {
  position: relative;
}

.label-inline {
  --padding-block: 0.1em;
  --padding-inline: 0.5em;
  --left: calc(#{bs.$form-select-padding-x} - var(--padding-inline) + 0.1em);

  position: absolute;
  top: calc(-0.9em - var(--padding-block));
  left: var(--left);
  z-index: 1;
  overflow: hidden;
  max-width: calc(100% - var(--left));
  padding: var(--padding-block) var(--padding-inline);
  border-radius: 0.5em;
  background-color: bs.$body-bg;
  font-size: 0.8em;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.invalid-feedback {
  position: absolute;
  top: 100%;
  left: 0;
  display: block;
  margin-top: 0;
  margin-left: 0.75rem;
}
</style>
