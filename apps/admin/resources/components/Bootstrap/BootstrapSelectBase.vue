<script lang="ts" setup>

import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { SelectOption } from './lib'

const props = withDefaults(defineProps<{
  id: string
  value: any
  options: SelectOption[]
  multiple?: boolean
  label?: string
  required?: boolean
  disabled?: MaybeRef<boolean>
  disabledPlaceholder?: string
  showEmptyItem?: boolean
  enableTags?: boolean
  emptyItemText?: string
}>(), {
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
  showEmptyItem: true,
  multiple: false,
  enableTags: false,
  emptyItemText: '',
})

const groupOptions = computed(() => {
  const allOptions: SelectOption[] = props.options
  const groupedData: any = {}
  allOptions.forEach((item) => {
    const group = item.group ? item.group : ''
    if (!groupedData[group]) {
      groupedData[group] = []
    }
    groupedData[group].push(item)
  })
  return groupedData
})

const emit = defineEmits<{
  (event: 'input', value: any): void
  (event: 'blur', e: any): void
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
      :multiple="multiple"
      @change="event => emit('blur', event)"
      @blur="event => emit('blur', event)"
      @input="event => emit('input', ((event.target as HTMLInputElement).value === 'undefined' ? undefined : (event.target as HTMLInputElement).value))"
    >
      <option v-if="disabled && disabledPlaceholder" selected disabled>{{ disabledPlaceholder }}</option>
      <option v-else-if="showEmptyItem && emptyItemText" value="undefined">{{ emptyItemText }}</option>
      <option v-else-if="showEmptyItem" :value="undefined" />
      <template v-if="!enableTags">
        <option v-for="option in options" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </template>
      <template v-else>
        <optgroup v-for="(optionInGroup, nameGroup) in groupOptions" :key="nameGroup" :label="nameGroup.toString()">
          <option v-for="option in optionInGroup" :key="option.value" :value="option.value">{{ option.label }}</option>
        </optgroup>
      </template>
    </select>
  </div>
</template>
