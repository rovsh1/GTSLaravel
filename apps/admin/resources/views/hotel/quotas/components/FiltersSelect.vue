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
  <div>
    <label :for="id" class="form-label label">{{ label }}</label>
    <select
      :id="id"
      :value="value"
      class="form-select"
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
  </div>
</template>
<style lang="scss" scoped>
.label {
  padding-left: 0.6em;
  font-size: 0.8em;
}
</style>
