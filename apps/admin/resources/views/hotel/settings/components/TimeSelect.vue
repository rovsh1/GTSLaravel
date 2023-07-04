<script setup lang="ts">

import { computed } from 'vue'

import { Time } from '~api/hotel/markup-settings'

type TimeValue = Time | null

const props = withDefaults(defineProps<{
  modelValue?: TimeValue
  from?: string
  to?: string
  label?: string
  disabled?: boolean
  required?: boolean
}>(), {
  modelValue: undefined,
  from: '00:00',
  to: '24:00',
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

const items = computed<string[]>(() => {
  const options = []
  for (let i = 0; i <= 24; i++) {
    let hour = `${i}`
    if (i < 10) {
      hour = `0${i}`
    }
    const hourTime = `${hour}:00`
    if (hourTime >= props.from && hourTime <= props.to) {
      options.push(hourTime)
    }

    const halfHourTime = `${hour}:30`
    if (halfHourTime >= props.from && halfHourTime <= props.to && i !== 24) {
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
