<script lang="ts" setup>

import { nextTick, onMounted, watch } from 'vue'

import { SelectOption } from '~components/Bootstrap/lib'

const props = withDefaults(defineProps<{
  id: string
  label: string
  labelMargin?: boolean
  labelOutline?: boolean
  value: string[]
  required?: boolean
  options: SelectOption[]
  disabled?: boolean
  closeAfterClickSelectAll?: boolean
}>(), {
  disabled: false,
  required: false,
  labelOutline: true,
  labelMargin: true,
  closeAfterClickSelectAll: false,
})

let multipleSelect: any = null
let currentValue: string[] | null = null

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

watch(() => props.value, (newValue: string[]) => {
  setValue(newValue)
})

watch(() => props.disabled, (newValue: boolean | null) => {
  setDisabled(newValue)
})

const emit = defineEmits<{
  (event: 'input', value: any): void
  (event: 'blur', value: any): void
}>()

onMounted(() => {
  nextTick(() => {
    multipleSelect = $(`#${props.id}`).multiselect({
      popupCls: 'dropdown-menu',
    })
    multipleSelect.change((val: any) => {
      currentValue = multipleSelect.val()
      if (props.closeAfterClickSelectAll && props.options.length === currentValue?.length) {
        $(`#${props.id}`).click()
      }
      setTimeout(() => {
        emit('input', multipleSelect.val())
        const elementForShowStatus = val.currentTarget.parentNode.getElementsByClassName('form-control')[0]
        emit('blur', { target: elementForShowStatus })
      }, 0)
    })
    if (props.value.length > 0) {
      setValue(props.value)
    }
    setDisabled(props.disabled)
  })
})

</script>
<template>
  <div :class="{ 'field-required': required }" style="position: relative;">
    <label :for="id" :class="{ 'form-label': labelMargin, 'label-inline': !labelOutline }">{{ label }}</label>
    <select :id="id" multiple>
      <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
    </select>
  </div>
</template>

<style lang="scss">
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

label {
  display: block;
}

.label-inline {
  --padding-block: 0.1em;
  --padding-inline: 0.5em;
  --left: calc(#{bs.$form-select-padding-x} - var(--padding-inline) + 0.1em);

  position: absolute;
  top: calc(-0.9em - var(--padding-block));
  left: var(--left);
  z-index: 1;
  overflow: hidden;
  max-width: calc(100% - var(--left));
  padding: var(--padding-block) var(--padding-inline);
  border-radius: 0.5em;
  background-color: bs.$body-bg;
  font-size: 0.8em;
  text-overflow: ellipsis;
  white-space: nowrap;
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
