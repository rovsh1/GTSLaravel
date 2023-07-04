<script setup lang="ts">
import { computed, onMounted } from 'vue'

import { formatPeriod } from '~lib/date'
import { useDateRangePicker } from '~lib/date-picker/date-picker'

const props = withDefaults(defineProps<{
  id: string
  label?: string
  required?: boolean
  disabled?: boolean
  value?: [Date, Date]
}>(), {
  required: false,
  label: undefined,
  disabled: undefined,
  value: undefined,
})

const emit = defineEmits<{
  (event: 'input', value: [Date, Date]): void
}>()

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

onMounted(() => {
  const periodInput = document.getElementById(props.id) as HTMLInputElement
  const picker = useDateRangePicker(periodInput)

  if (props.value) {
    picker.setDateRange(props.value[0], props.value[1])
  } else {
    picker.clearSelection()
  }

  picker.on('selected', (date1: any, date2: any) => {
    emit('input', [date1.dateInstance, date2.dateInstance])
  })
})

</script>

<template>
  <div :class="{ 'field-required': required }">
    <label v-if="label" :for="id">{{ label }}</label>
    <input :id="id" class="form-control" :required="required" :disabled="disabled" :value="displayValue">
  </div>
</template>
