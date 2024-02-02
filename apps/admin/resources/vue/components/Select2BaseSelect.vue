<script lang="ts" setup>

import { onMounted, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'

import { useSelectElement } from '~lib/select-element/select-element'

const props = withDefaults(defineProps<{
  id: string
  value: any
  options: SelectOption[]
  name?: string
  label?: string
  required?: boolean
  disabled?: MaybeRef<boolean>
  disabledPlaceholder?: string
  showEmptyItem?: boolean
  parent?: string
  enableTags?: boolean
  enableMultiple?: boolean
  emptyItemText?: string
}>(), {
  name: undefined,
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
  showEmptyItem: true,
  parent: undefined,
  enableTags: false,
  enableMultiple: false,
  emptyItemText: '',
})

const changegValue = ref(props.value)
let select2: any = null

const emit = defineEmits<{
  (event: 'input', value: any): void
  (event: 'blur', value: any): void
}>()

const clearComponentValue = () => {
  if (select2) {
    select2.val('').trigger('change')
  }
}

const setValue = (value: any) => {
  if (select2) {
    if (value) {
      select2.val(value).trigger('change')
    } else if (props.emptyItemText || props.disabledPlaceholder) {
      select2.val('undefined').trigger('change')
    } else {
      select2.val('').trigger('change')
    }
  }
}

watch(() => props.value, (newValue) => {
  setValue(newValue)
})

onMounted(async () => {
  $(`#${props.id}`).parent().addClass(`${props.id}-wrapper--init`)
  const options = {
    dropdownParent: `${props.id}-wrapper--init`,
    multiple: props.enableMultiple,
  }
  select2 = (await useSelectElement(document.querySelector<HTMLSelectElement>(`#${props.id}`), {
    ...options,
  }))?.select2Instance

  setValue(props.value)

  select2.change((val: any) => {
    if (options.multiple) {
      const values = $(`#${props.id}`).val() as string[]
      emit('input', values)
    } else {
      emit('input', val.target.value === 'undefined' ? undefined : val.target.value)
    }
    emit('blur', val)
  })

  select2.on('select2:close', (e: any) => {
    emit('blur', e)
  })
})

defineExpose({
  clearComponentValue,
})

</script>
<template>
  <BootstrapSelectBase
    :id="id"
    :label="label"
    :name="name"
    :options="options"
    :value="changegValue"
    :multiple="enableMultiple"
    :required="required"
    :disabled="disabled"
    :disabled-placeholder="disabledPlaceholder"
    :show-empty-item="showEmptyItem"
    :empty-item-text="emptyItemText"
    :enable-tags="enableTags"
    :with-select2="true"
  />
</template>

<style lang="scss">
.select2-selection__choice {
  display: flex;
  align-items: center;
  margin-left: 0.375rem;
}

.select2-selection__choice__remove {
  border: none;
  background: none;
  outline: none;
}
</style>
