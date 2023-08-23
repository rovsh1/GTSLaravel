<script lang="ts" setup>

import { onMounted, watch } from 'vue'

import { SelectOption } from '~components/Bootstrap/lib'

const props = withDefaults(defineProps<{
  id: string
  label: string
  labelMargin?: boolean
  value: string[]
  required?: boolean
  options: SelectOption[]
  disabled?: boolean
}>(), {
  disabled: false,
  required: false,
  labelMargin: true,
})

let multipleSelect:any = null
let currentValue:string[] | null = null

const setValue = (value: string[]) => {
  const valuesEqual = JSON.stringify(value) === JSON.stringify(currentValue)
  if (!valuesEqual) {
    multipleSelect.val(value)
    multipleSelect.change()
  }
}

const setDisabled = (value: boolean | null) => {
  if (value) {
    multipleSelect.multiselect('disable')
  } else {
    multipleSelect.multiselect('enable')
  }
}

watch(() => props.value, (newValue:string[]) => {
  setValue(newValue)
})

watch(() => props.disabled, (newValue:boolean | null) => {
  setDisabled(newValue)
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
  if (props.value.length > 0) {
    setValue(props.value)
  }
  setDisabled(props.disabled)
})

</script>
<template>
  <div :class="{ 'field-required': required }">
    <label :for="id" :class="{ 'form-label': labelMargin }">{{ label }}</label>
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

.ui-multiselect.disabled {
  opacity: 1;

  .label,
  .dropdown-menu .dropdown-item,
  .select {
    opacity: 0.6;
  }
}

</style>
