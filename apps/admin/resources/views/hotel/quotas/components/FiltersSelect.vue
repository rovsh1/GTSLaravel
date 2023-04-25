<script lang="ts" setup>
import { nanoid } from 'nanoid'

type Option = {
  value: string | number
  label: string
}

defineProps<{
  value: Option['value'] | null
  options: Option[]
  label: string
}>()

const emit = defineEmits<{
  (event: 'input', value: Option['value']): void
}>()

const id = `field-${nanoid()}`
</script>
<template>
  <fieldset class="fieldset">
    <label :for="id" class="label">{{ label }}</label>
    <select
      :id="id"
      :value="value"
      class="select"
      @input="event => emit('input', (event.target as HTMLInputElement).value)"
    >
      <option v-if="value === null" selected disabled>
        Не выбрано
      </option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
  </fieldset>
</template>
<style lang="scss" scoped>
.fieldset {
  display: flex;
  flex-flow: column;
  gap: 0.25em;
}

.label {
  padding-left: 0.6em;
  font-size: 0.8em;
}

.select {
  padding: 0.25em;
  padding-right: 1em;
  border: 1px solid silver;
  border-radius: 0.375em;
}
</style>
