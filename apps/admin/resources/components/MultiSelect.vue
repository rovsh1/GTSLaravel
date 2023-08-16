<script lang="ts" setup>

import { onMounted, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { SelectOption } from '~components/Bootstrap/lib'

const props = withDefaults(defineProps<{
  id: string
  label: string
  value: string[]
  required?: boolean
  options: SelectOption[]
  disabled?: MaybeRef<boolean>
}>(), {
  disabled: false,
  required: false,
})

let multipleSelect:any = null
let currentValue:string[] | null = null

watch(() => props.value, (newValue:string[] | null) => {
  const valuesEqual = JSON.stringify(newValue) === JSON.stringify(currentValue)
  if (!valuesEqual) {
    multipleSelect.val(newValue)
    multipleSelect.change()
  }
})

const emit = defineEmits<{
  (event: 'input', value: any): void
  (event: 'blur', value: any): void
}>()

onMounted(() => {
  multipleSelect = $(`#${props.id}`).multiselect({
    popupCls: 'dropdown-menu',
  })
  multipleSelect.change((val: any) => {
    currentValue = multipleSelect.val()
    emit('input', multipleSelect.val())
    const elementForShowStatus = val.currentTarget.parentNode.getElementsByClassName('form-control')[0]
    emit('blur', { target: elementForShowStatus })
  })
})

</script>
<template>
  <div :class="{ 'field-required': required }">
    <label :for="id" class="form-label">{{ label }}</label>
    <select :id="id" multiple>
      <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
    </select>
  </div>
</template>

<style lang="scss">
label {
  display: block;
}

.ui-multiselect {
  width: 100%;
}
</style>
