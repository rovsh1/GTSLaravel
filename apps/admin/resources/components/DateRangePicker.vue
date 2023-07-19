<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue'

import { Litepicker } from 'litepicker'
import { DateTime, Interval } from 'luxon'

import { DatePeriod } from '~api/hotel/markup-settings'

import { formatPeriod, parseAPIDate } from '~lib/date'
import { useDateRangePicker } from '~lib/date-picker/date-picker'

const props = withDefaults(defineProps<{
  id: string
  label?: string
  required?: boolean
  disabled?: boolean
  value?: [Date, Date]
  lockPeriods?: DatePeriod[]
  editableId?: number
}>(), {
  required: false,
  label: undefined,
  disabled: undefined,
  value: undefined,
  lockPeriods: undefined,
  editableId: undefined,
})

const emit = defineEmits<{
  (event: 'input', value: [Date, Date]): void
}>()

const localValue = computed(() => props.value)
const displayValue = computed(() => {
  if (props.value) {
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

const lockDaysFilter = (inputDate: any) => {
  if (inputDate === null) {
    return false
  }
  return lockPeriods.value?.find((period, index): boolean => {
    const isSamePeriod = editableId.value === index
    const { from, to } = period
    const start = parseAPIDate(from).startOf('day')
    const end = parseAPIDate(to).endOf('day')
    const inputDateTime = DateTime.fromJSDate(inputDate.toJSDate())

    return !isSamePeriod && Interval.fromDateTimes(start, end).contains(inputDateTime)
  }) !== undefined
}

let picker: Litepicker

onMounted(() => {
  const periodInput = document.getElementById(props.id) as HTMLInputElement
  picker = useDateRangePicker(periodInput, { lockDaysFilter })

  picker.on('before:show', () => {
    if (localValue.value) {
      picker.setDateRange(localValue.value[0], localValue.value[1])
    } else {
      picker.clearSelection()
    }
  })

  picker.on('selected', (date1: any, date2: any) => {
    emit('input', [date1.dateInstance, date2.dateInstance])
  })
})

onUnmounted(() => {
  if (picker) {
    picker.destroy()
  }
})
</script>

<template>
  <div :class="{ 'field-required': required }">
    <label v-if="label" :for="id">{{ label }}</label>
    <input :id="id" class="form-control" :required="required" :disabled="disabled" :value="displayValue">
  </div>
</template>
