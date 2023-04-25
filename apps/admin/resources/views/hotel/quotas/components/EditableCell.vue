<script lang="ts" setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'

type CellKey = string | null

const props = defineProps<{
  cellKey: string
  activeKey: CellKey
  value: string
  max: number
  disabled: boolean
  inRange: boolean
}>()

const emit = defineEmits<{
  (event: 'active-key', value: CellKey): void
  (event: 'range-key', value: CellKey): void
  (event: 'value', value: number): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)

const shift = ref(false)

const handleInput = (input: HTMLInputElement) => {
  input.focus()
  input.select()
}

watch(inputRef, (element) => {
  if (element === null) return
  handleInput(element)
})

const handleChange = (target: EventTarget | null) => {
  if (target === null) return
  const { value } = target as HTMLInputElement
  if (value === '') return
  const number = Number(value)
  if (isNaN(number)) return
  emit('value', number)
}

const handleButtonClick = (event: MouseEvent) => {
  if (shift.value) {
    event.preventDefault()
    emit('range-key', props.cellKey)
  } else {
    emit('active-key', props.cellKey)
  }
}

const enableShiftMode = () => {
  shift.value = true
}

const disableShiftMode = () => {
  shift.value = false
}

onMounted(() => {
  window.addEventListener('keydown', enableShiftMode)
  window.addEventListener('keyup', disableShiftMode)
})

onUnmounted(() => {
  window.removeEventListener('keydown', enableShiftMode)
  window.removeEventListener('keyup', disableShiftMode)
})
</script>
<template>
  <label v-if="activeKey === cellKey">
    <input
      :ref="(element) => inputRef = element as HTMLInputElement"
      :value="value"
      class="editableCellInput"
      type="number"
      :max="max"
      @change="(event) => handleChange(event.target)"
      @blur="(event) => {
        if (shift) {
          event.preventDefault()
          inputRef?.focus()
          return
        }
        emit('active-key', null)
        emit('range-key', null)
      }"
      @keydown.esc="inputRef?.blur()"
      @keydown.enter="inputRef?.blur()"
    />
  </label>
  <button
    v-else
    type="button"
    class="editableDataCell"
    :class="{ inRange }"
    :disabled="disabled"
    @click="handleButtonClick"
  >
    <slot />
  </button>
</template>
<style lang="scss" scoped>
@use './shared' as shared;

%cell {
  @include shared.cell;
}

%data-cell {
  @include shared.data-cell;
}

.editableCellInput {
  @extend %data-cell;

  width: 100%;
  font-size: inherit;
  appearance: textfield;

  &::-webkit-outer-spin-button,
  &::-webkit-inner-spin-button {
    margin: 0;
    appearance: none;
  }
}

.editableDataCell {
  @extend %data-cell;

  width: 100%;
  border: 2px solid transparent;
  border-radius: 2px;
  background: unset;

  &:not(:disabled) {
    &:hover {
      background-color: rgba(black, 0.1);
    }
  }

  &,
  &:disabled {
    color: inherit;
  }

  &.inRange {
    border-color: rgba(blue, 0.3);
  }
}
</style>
