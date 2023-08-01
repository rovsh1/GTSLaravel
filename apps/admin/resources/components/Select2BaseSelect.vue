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
}>(), {
  label: '',
  disabled: false,
  required: false,
  disabledPlaceholder: undefined,
  showEmptyItem: true,
})

const emit = defineEmits<{
  (event: 'input', value: SelectedValue): void
}>()

onMounted(() => {
  $(`#${props.id}`)
    .select2()
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
