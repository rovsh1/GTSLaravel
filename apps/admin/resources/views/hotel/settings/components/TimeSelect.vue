<script setup lang="ts">

import { computed, ref } from 'vue'

const props = withDefaults(defineProps<{
  modelValue?: string
  from?: string
  to?: string
  label?: string
  disabled?: boolean
}>(), {
  modelValue: undefined,
  from: '00:00',
  to: '24:00',
  label: undefined,
  disabled: false,
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const localValue = computed<string | undefined>({
  get: () => props.modelValue,
  set: (value: string | undefined) => emit('update:modelValue', value as string),
})

const items = ref<string[]>([])
for (let i = 0; i <= 24; i++) {
  let hour = `${i}`
  if (i < 10) {
    hour = `0${i}`
  }
  const hourTime = `${hour}:00`
  if (hourTime >= props.from && hourTime <= props.to) {
    items.value.push(hourTime)
  }

  const halfHourTime = `${hour}:30`
  if (halfHourTime >= props.from && halfHourTime <= props.to && i !== 24) {
    items.value.push(halfHourTime)
  }
}

</script>

<template>
  <div>
    <label v-if="label" for="s">{{ label }}</label>
    <select
      v-model="localValue"
      class="form-select form-control"
      :disabled="disabled"
      required
    >
      <option v-for="item in items" :key="item">{{ item }}</option>
    </select>
  </div>
</template>
