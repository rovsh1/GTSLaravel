<script lang="ts" setup>
import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { nanoid } from 'nanoid'

import BootstrapSelectBase from './BootstrapSelectBase.vue'

import { SelectOption } from './lib'

const props = withDefaults(defineProps<{
  value: SelectOption['value']
  options: SelectOption[]
  label: string
  disabled?: MaybeRef<boolean>
  allowDeselect?: boolean
  unselectedLabel?: string
  required?: boolean
}>(), {
  disabled: false,
  allowDeselect: false,
  unselectedLabel: 'Не выбрано',
  required: false,
})

const emit = defineEmits<{
  (event: 'input', value: SelectOption['value']): void
}>()

const id = `compact-select-${nanoid()}`

const unselected: SelectOption = {
  label: props.unselectedLabel,
  value: '',
}

const computedOptions = computed<SelectOption[]>(() => (props.allowDeselect ? [
  unselected,
  ...props.options,
] : props.options))
</script>
<template>
  <div class="compactSelect">
    <BootstrapSelectBase
      :id="id"
      :value="value"
      :options="computedOptions"
      :show-empty-item="false"
      :disabled="disabled"
      :required="required"
      @input="value => emit('input', value as string | number)"
    >
      <template #label>
        <label :for="id" class="label a1">{{ label }}</label>
      </template>
    </BootstrapSelectBase>
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.compactSelect {
  position: relative;
}

.label {
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
</style>
