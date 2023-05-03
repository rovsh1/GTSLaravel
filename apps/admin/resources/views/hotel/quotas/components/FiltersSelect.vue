<script lang="ts" setup>
import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { nanoid } from 'nanoid'

type Option = {
  value: string | number | ''
  label: string
}

const props = withDefaults(defineProps<{
  value: Option['value']
  options: Option[]
  label: string
  allowDeselect?: boolean
  disabled?: MaybeRef<boolean>
}>(), {
  allowDeselect: false,
  disabled: false,
})

const emit = defineEmits<{
  (event: 'input', value: Option['value']): void
}>()

const id = `field-${nanoid()}`

const unselected: Option = {
  label: 'Не выбрано',
  value: '',
}

const computedOptions = computed<Option[]>(() => (props.allowDeselect ? [
  unselected,
  ...props.options,
] : props.options))
</script>
<template>
  <div>
    <label :for="id" class="form-label label">{{ label }}</label>
    <select
      :id="id"
      :value="value"
      class="form-select"
      :disabled="disabled as boolean"
      @input="event => emit('input', (event.target as HTMLInputElement).value)"
    >
      <option
        v-for="option in computedOptions"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
  </div>
</template>
<style lang="scss" scoped>
.label {
  padding-left: 0.6em;
  font-size: 0.8em;
}
</style>
