<script lang="ts" setup>

import { computed, nextTick, ref, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { SelectOption } from './lib'

const props = withDefaults(defineProps<{
  id: string
  value: any
  options: SelectOption[]
  multiple?: boolean
  label?: string
  name?: string
  required?: boolean
  disabled?: MaybeRef<boolean>
  disabledPlaceholder?: string
  showEmptyItem?: boolean
  enableTags?: boolean
  emptyItemText?: string
  withSelect2?: boolean
}>(), {
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
  showEmptyItem: true,
  multiple: false,
  enableTags: false,
  emptyItemText: '',
  withSelect2: false,
  name: undefined,
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

const localValue = ref<any>(props.value)

const selectElement = ref(null)

watchEffect(() => {
  localValue.value = props.value
})

const emit = defineEmits<{
  (event: 'input', value: any, e: any): void
}>()

const handleChangeValue = () => {
  nextTick(() => {
    emit('input', (localValue.value === 'undefined' ? undefined : localValue.value), selectElement.value)
  })
}

</script>
<template>
  <div :class="{ 'field-required': required }">
    <template v-if="$slots['label']">
      <slot name="label" />
    </template>
    <label v-else-if="label" :for="id" class="form-label">{{ label }}</label>
    <select
      :id="id"
      ref="selectElement"
      v-model="localValue"
      :name="name"
      class="form-select form-control"
      :disabled="disabled as boolean"
      :required="required as boolean"
      :multiple="multiple"
      @change="handleChangeValue"
    >
      <option v-if="disabled && disabledPlaceholder" :value="withSelect2 ? 'undefined' : undefined">{{ disabledPlaceholder }}</option>
      <option v-else-if="showEmptyItem && emptyItemText !== ''" :value="withSelect2 ? 'undefined' : undefined">{{ emptyItemText }}</option>
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

<style lang="scss" scoped>
.field-required {
  width: 100%;
}
</style>
