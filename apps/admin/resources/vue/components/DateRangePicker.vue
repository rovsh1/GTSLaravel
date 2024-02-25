<script setup lang="ts">
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'

import { onClickOutside } from '@vueuse/core'
import { compareJSDate, formatDateToAPIDate, formatPeriod, parseAPIDate } from 'gts-common/helpers/date'
import { useDateRangePicker } from 'gts-common/widgets/date-picker/date-picker'
import { Litepicker } from 'litepicker'
import { DateTime } from 'luxon'

import { DateResponse } from '~api'
import { DatePeriod } from '~api/hotel/markup-settings'

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
      date_start: formatDateToAPIDate(props.value[0]),
      date_end: formatDateToAPIDate(props.value[1]),
    }
    return formatPeriod(period)
  }
  return ''
})

const editableId = computed(() => props.editableId)

const minDateLocale = computed(() => (props.minDate ? parseAPIDate(props.minDate).startOf('day') : undefined))
const maxDateLocale = computed(() => (props.maxDate ? parseAPIDate(props.maxDate).endOf('day') : undefined))

const setBlockPeriodsValue = () => {
  const result: [string, string][] = []
  props.lockPeriods?.forEach((lockPeriod, index) => {
    if (editableId.value !== index) {
      result.push([
        parseAPIDate(lockPeriod.from).startOf('day').toFormat('yyyy-LL-dd'),
        parseAPIDate(lockPeriod.to).startOf('day').toFormat('yyyy-LL-dd'),
      ])
    }
  })

  return result
}

const blockedPeriods = ref(setBlockPeriodsValue())

const isValidSingleDateOrRange = (date1: any, date2: any): boolean => {
  const startDate = DateTime.fromJSDate(date1.dateInstance).startOf('day')
  const endDate = DateTime.fromJSDate(props.singleMode ? date1.dateInstance : date2.dateInstance).endOf('day')
  const selectedPeriodDays = []
  if (startDate.equals(endDate)) {
    selectedPeriodDays.push(startDate)
  } else {
    let currentDate = startDate
    while (currentDate <= endDate) {
      selectedPeriodDays.push(currentDate)
      currentDate = currentDate.plus({ days: 1 })
    }
  }
  let isValidSelectedPeriod = true
  selectedPeriodDays.forEach((dayFromSelectedPeriod) => {
    const isDateInRange = blockedPeriods.value?.some((range) => {
      const startDateTime = DateTime.fromISO(range[0])
      const endDateTime = DateTime.fromISO(range[1])
      return dayFromSelectedPeriod >= startDateTime && dayFromSelectedPeriod <= endDateTime
    })
    if (minDateLocale.value) {
      if (dayFromSelectedPeriod < minDateLocale.value) {
        isValidSelectedPeriod = false
        return
      }
    }
    if (maxDateLocale.value) {
      if (dayFromSelectedPeriod > maxDateLocale.value) {
        isValidSelectedPeriod = false
        return
      }
    }
    if (isDateInRange) {
      isValidSelectedPeriod = false
    }
  })
  if (!isValidSelectedPeriod) {
    return false
  }
  return true
}

onClickOutside(inputRef, (event: MouseEvent) => {
  const dropDownLitepickerElement = document.querySelector('.litepicker')
  if (!dropDownLitepickerElement?.contains(event.target as Node)) {
    emit('clickOutside')
  }
})

let picker: Litepicker

watch([() => props.lockPeriods, () => props.editableId], () => {
  blockedPeriods.value = setBlockPeriodsValue()
  picker.setLockDays(blockedPeriods.value)
})

watch([() => props.minDate, () => props.maxDate], () => {
  picker.setOptions({
    minDate: minDateLocale.value?.toISODate() || undefined,
  })
  picker.setOptions({
    maxDate: maxDateLocale.value?.toISODate() || undefined,
  })
})

onMounted(() => {
  nextTick(() => {
    const periodInput = document.getElementById(props.id) as HTMLInputElement

    picker = useDateRangePicker(periodInput, {
      lockDays: blockedPeriods.value,
      singleMode: props.singleMode,
      minDate: minDateLocale.value?.toISODate() || undefined,
      maxDate: maxDateLocale.value?.toISODate() || undefined,
    })

    picker.on('before:show', () => {
      if (localValue.value) {
        if (Array.isArray(localValue.value)) {
          picker.setDateRange(
            DateTime.fromJSDate(localValue.value[0]).toISODate(),
            DateTime.fromJSDate(localValue.value[1]).toISODate(),
          )
        } else {
          picker.setDate(DateTime.fromJSDate(localValue.value).toISODate())
        }
      } else {
        picker.clearSelection()
      }
    })

    picker.on('selected', (date1: any, date2: any) => {
      if (props.singleMode) {
        if (!localValue.value || (!Array.isArray(localValue.value) && !compareJSDate(localValue.value, date1.dateInstance))) {
          if (isValidSingleDateOrRange(date1, date2)) {
            emit('input', date1.dateInstance)
          } else {
            picker.clearSelection()
          }
        }
      } else if (!localValue.value || (Array.isArray(localValue.value) && (
        !compareJSDate(localValue.value[0], date1.dateInstance) || !compareJSDate(localValue.value[1], date2.dateInstance)
      ))) {
        if (isValidSingleDateOrRange(date1, date2)) {
          emit('input', props.singleMode ? date1.dateInstance : [date1.dateInstance, date2.dateInstance])
        } else {
          picker.clearSelection()
        }
      }
    })

    if (props.setInputFocus) {
      periodInput.focus()
      periodInput.click()
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
      @keydown.enter.stop="emit('pressEnter')"
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
