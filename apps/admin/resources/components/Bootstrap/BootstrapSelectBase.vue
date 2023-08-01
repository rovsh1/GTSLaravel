<script lang="ts" setup>

import { MaybeRef } from '@vueuse/core'

import { SelectOption } from './lib'

export type SelectedValue = SelectOption['value'] | undefined

withDefaults(defineProps<{
  id: string
  value: SelectedValue
  options: SelectOption[]
  label?: string
  disabled?: MaybeRef<boolean>
  required?: boolean
  disabledPlaceholder?: string
}>(), {
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
})

const emit = defineEmits<{
  (event: 'input', value: SelectedValue): void
}>()
</script>
<template>
  <div :class="{ 'field-required': required }">
    <template v-if="$slots['label']">
      <slot name="label" />
    </template>
    <label v-else-if="label" :for="id" class="form-label">{{ label }}</label>
    <select
      :id="id"
      :value="value"
      class="form-select form-control"
      :disabled="disabled as boolean"
      :required="required as boolean"
      @input="event => emit('input', (event.target as HTMLInputElement).value)"
    >
      <option v-if="disabled && disabledPlaceholder" selected disabled>{{ disabledPlaceholder }}</option>
      <option v-else :value="undefined" />
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
