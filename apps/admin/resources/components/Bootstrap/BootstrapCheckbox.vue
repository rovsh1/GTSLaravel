<script lang="ts" setup>
import { MaybeRef } from '@vueuse/core'
import { nanoid } from 'nanoid'

withDefaults(defineProps<{
  value: boolean
  label: string
  disabled?: MaybeRef<boolean>
}>(), {
  disabled: false,
})

const emit = defineEmits<{
  (event: 'input', value: boolean): void
}>()

const id = `checkbox-${nanoid()}`

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
      :checked="value"
      :disabled="disabled as boolean"
      @change="event => handleChange(event)"
    >
    <label class="form-check-label" :for="id">
      {{ label }}
    </label>
  </div>
</template>
