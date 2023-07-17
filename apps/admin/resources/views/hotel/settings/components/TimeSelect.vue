<script setup lang="ts">

import { computed } from 'vue'

import { Time } from '~api/hotel/markup-settings'

type TimeValue = Time | null

const props = withDefaults(defineProps<{
  modelValue?: TimeValue
  min?: Time
  max?: Time
  label?: string
  disabled?: boolean
  required?: boolean
}>(), {
  modelValue: undefined,
  min: '00:00',
  max: '24:00',
  label: undefined,
  disabled: false,
  required: false,
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: TimeValue): void
  (e: 'change'): void
}>()

const localValue = computed<TimeValue>({
  get: () => props.modelValue as TimeValue,
  set: (value: TimeValue) => {
    emit('update:modelValue', value)
    emit('change')
  },
})

type TimeHour = `${number}` | `${number}${number}`

const items = computed<string[]>(() => {
  const options = []
  for (let i = 0; i <= 24; i++) {
    let hour: TimeHour = `${i}`
    if (i < 10) {
      hour = `0${i}`
    }
    const hourTime: Time = `${hour}:00`
    if (hourTime >= props.min && hourTime <= props.max) {
      options.push(hourTime)
    }

    const halfHourTime: Time = `${hour}:30`
    if (halfHourTime >= props.min && halfHourTime <= props.max && i !== 24) {
      options.push(halfHourTime)
    }
  }
  return options
})

</script>

<template>
  <div :class="{ 'field-required': required }">
    <label v-if="label" for="s">{{ label }}</label>
    <select
      v-model="localValue"
      class="form-select form-control"
      :disabled="disabled"
      :required="required"
    >
      <option :value="null" />
      <option v-for="item in items" :key="item">{{ item }}</option>
    </select>
  </div>
</template>
