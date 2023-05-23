<script lang="ts" setup>
import { MaybeRef } from '@vueuse/core'

import { SelectOption } from './lib'

withDefaults(defineProps<{
  id: string
  value: SelectOption['value']
  options: SelectOption[]
  label?: string
  disabled?: MaybeRef<boolean>
  required?: boolean
}>(), {
  label: '',
  disabled: false,
  required: false,
})

const emit = defineEmits<{
  (event: 'input', value: SelectOption['value']): void
}>()
</script>
<template>
  <div :class="{ 'field-required': required }">
    <template v-if="$slots['label']">
      <slot name="label" />
    </template>
    <label v-else :for="id" class="form-label">{{ label }}</label>
    <select
      :id="id"
      :value="value"
      class="form-select"
      :disabled="disabled as boolean"
      :required="required"
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
  </div>
</template>
