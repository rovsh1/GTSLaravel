<script lang="ts" setup>

import { onMounted, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'

const props = withDefaults(defineProps<{
  id: string
  value: any
  options: SelectOption[]
  label?: string
  required?: boolean
  disabled?: MaybeRef<boolean>
  disabledPlaceholder?: string
  showEmptyItem?: boolean
  parent?: string
  enableTags?: boolean
  enableMultiple?: boolean
}>(), {
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
  showEmptyItem: true,
  parent: undefined,
  enableTags: false,
  enableMultiple: false,
})

const changegValue = ref(props.value)
let select2: any = null

const emit = defineEmits<{
  (event: 'input', value: any): void
  (event: 'blur', value: any): void
}>()

const clearComponentValue = () => {
  if (select2) {
    select2.val(null).trigger('change')
  }
}

onMounted(() => {
  const options = {
    dropdownParent: props.parent,
    multiple: !!props.enableMultiple,
    closeOnSelect: !props.enableMultiple,
  }
  select2 = $(`#${props.id}`)
    .select2(options)

  select2.change((val: any) => {
    if (options.multiple) {
      const values = $(`#${props.id}`).val() as string[]
      emit('input', values)
    } else {
      emit('input', val.target.value)
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
    :options="options"
    :value="changegValue"
    :multiple="enableMultiple"
    :required="required"
    :disabled="disabled"
    :disabled-placeholder="disabledPlaceholder"
    :show-empty-item="showEmptyItem"
    :enable-tags="enableTags"
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
