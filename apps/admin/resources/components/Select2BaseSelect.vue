<script lang="ts" setup>

import { onMounted } from 'vue'

import { MaybeRef } from '@vueuse/core'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectedValue, SelectOption } from '~components/Bootstrap/lib'

const props = withDefaults(defineProps<{
  id: string
  value: SelectedValue
  options: SelectOption[]
  label?: string
  required?: boolean
  disabled?: MaybeRef<boolean>
  disabledPlaceholder?: string
  showEmptyItem?: boolean
  parent?: string
}>(), {
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
  showEmptyItem: true,
  parent: undefined,
})

const emit = defineEmits<{
  (event: 'input', value: SelectedValue): void
}>()

onMounted(() => {
  const options = {
    dropdownParent: props.parent,
  }
  $(`#${props.id}`)
    .select2(options)
    .change((val: any) => emit('input', val.target.value))
})

</script>
<template>
  <BootstrapSelectBase
    :id="id"
    :label="label"
    :options="options"
    :value="value"
    :required="required"
    :disabled="disabled"
    :disabled-placeholder="disabledPlaceholder"
    :show-empty-item="showEmptyItem"
  />
</template>
