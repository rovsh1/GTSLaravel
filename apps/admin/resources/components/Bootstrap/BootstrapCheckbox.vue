<script lang="ts" setup>
import { MaybeRef } from '@vueuse/core'
import { nanoid } from 'nanoid'

withDefaults(defineProps<{
  value: boolean
  label: string
  name?: string
  disabled?: MaybeRef<boolean>
  id?: string
}>(), {
  name: undefined,
  disabled: false,
  id: `checkbox-${nanoid()}`,
})

const emit = defineEmits<{
  (event: 'input', value: boolean): void
}>()

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  emit('input', target.checked)
}
</script>
<template>
  <div class="form-check">
    <input
      :id="id"
      class="form-check-input"
      type="checkbox"
      :name="name"
      :checked="value"
      :disabled="disabled as boolean"
      @change="event => handleChange(event)"
    >
    <label class="form-check-label" :for="id">
      {{ label }}
    </label>
  </div>
</template>
