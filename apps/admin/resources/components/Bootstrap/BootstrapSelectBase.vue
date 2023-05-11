<script lang="ts" setup>
import { MaybeRef } from '@vueuse/core'

import { SelectOption } from './lib'

withDefaults(defineProps<{
  id: string
  value: SelectOption['value']
  options: SelectOption[]
  label?: string
  disabled?: MaybeRef<boolean>
}>(), {
  label: '',
  disabled: false,
})

const emit = defineEmits<{
  (event: 'input', value: SelectOption['value']): void
}>()
</script>
<template>
  <template v-if="$slots['label']">
    <slot name="label" />
  </template>
  <label v-else :for="id" class="form-label">{{ label }}</label>
  <select
    :id="id"
    :value="value"
    class="form-select"
    :disabled="disabled as boolean"
    @input="event => emit('input', (event.target as HTMLInputElement).value)"
  >
    <option
      v-for="option in options"
      :key="option.value"
      :value="option.value"
    >
      {{ option.label }}
    </option>
  </select>
</template>
